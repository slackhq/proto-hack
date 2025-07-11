package main

import (
	"bytes"
	"compress/flate"
	"compress/zlib"
	"flag"
	"fmt"
	"io"
	"os"
	"path/filepath"
	"sort"
	"strconv"
	"strings"

	"github.com/golang/protobuf/proto"
	desc "github.com/golang/protobuf/protoc-gen-go/descriptor"
	ppb "github.com/golang/protobuf/protoc-gen-go/plugin"

	"github.com/slackhq/proto-hack/opt"
)

const (
	specialPrefix  = "XXX_"
	reservedPrefix = "pb_"
	genDebug       = false
	libNs          = "\\Protobuf"
	libNsInternal  = libNs + "\\Internal"
)

var (
	version          = "undefined" // go build -ldflags "-X main.version=1"
	fversion         = flag.Bool("version", false, "print version and exit")
	reservedKeywords = []string{"eval", "isset", "unset", "empty", "const", "new", "and", "or",
		"xor", "as", "print", "throw", "array", "instanceof", "trait", "class", "interface", "static", "self",
		"int", "bool", "classname", "dict", "vec", "dynamic", "float", "keyset", "nothing", "noreturn", "num", "enum",
		"shape", "string", "Vector", "Map", "Set", "varray", "darray", "Awaitable", "Iterable", "Container", "KeyedContainer",
		"Traversable", "KeyedTraversable", "Iterable", "KeyedIterable", "Iterator", "KeyedIterator", "AsyncIterator",
		"AsyncKeyedIterator", "AsyncGenerator", "Generator", "FormatString", "BuiltinEnum", "Throwable", "DateTime",
		"stdClass", "DateTimeImmutable", "Stringish", "XHPChild", "IMemoizeParam", "typename", "IDisposable",
		"IAsyncDisposable", "ImmVector", "Set", "ImmSet", "ImmMap", "Pair", "ConstVector", "Collection", "ConstMap",
		"ConstCollection", "Class", "ClassAttribute", "EnumAttribute", "TypeAliasAttribute", "FunctionAttribute", "MethodAttribute",
		"InstancePropertyAttribute", "StaticPropertyAttribute", "ParameterAttribute", "TypeParameterAttribute", "FileAttribute",
		"TypeConstantAttribute", "Function", "tuple", "echo", "assert", "fun", "invariant", "invariant_violation", "inst_meth", "class_meth",
		"meth_caller", "varray_or_darray", "callable", "object", "dynamic", "this", "mixed", "resource", "null", "namespace"}
)

func main() {
	flag.Parse()
	if *fversion {
		fmt.Println("version", version)
		return
	}
	var buf bytes.Buffer
	_, err := buf.ReadFrom(os.Stdin)
	if err != nil {
		panic(fmt.Errorf("error reading from stdin: %v", err))
	}
	out, err := codeGenerator(buf.Bytes())
	if err != nil {
		panic(err)
	}
	os.Stdout.Write(out)
}

func codeGenerator(b []byte) ([]byte, error) {
	req := ppb.CodeGeneratorRequest{}
	err := proto.Unmarshal(b, &req)
	if err != nil {
		return nil, fmt.Errorf("error unmarshaling CodeGeneratorRequest: %v", err)
	}
	resp := gen(&req)
	out, err := proto.Marshal(resp)
	if err != nil {
		return nil, fmt.Errorf("error marshaling CodeGeneratorResponse: %v", err)
	}
	return out, nil
}

type syntax int

const (
	SyntaxUnknown syntax = 0
	SyntaxProto2  syntax = 2
	SyntaxProto3  syntax = 3
)

func gen(req *ppb.CodeGeneratorRequest) *ppb.CodeGeneratorResponse {
	resp := &ppb.CodeGeneratorResponse{}
	features := uint64(ppb.CodeGeneratorResponse_FEATURE_PROTO3_OPTIONAL)
	resp.SupportedFeatures = &features
	fileToGenerate := map[string]bool{}
	for _, f := range req.FileToGenerate {
		fileToGenerate[f] = true
	}

	allowProto2 := false
	genService := false
	filePerEntity := false
	if req.GetParameter() != "" {
		opts := strings.Split(req.GetParameter(), ",")
		for _, opt := range opts {
			switch opt {
			case "plugin=grpc":
				genService = true
			case "file_per_entity":
				filePerEntity = true
			case "allow_proto2_dangerous":
				// proto2 is mostly fully supported, except for a few edge cases
				// such as extensions, packed/unpaced enums and very large values
				// for enums, see conformance/failures.txt for which tests are still failing.
				allowProto2 = true
			default:
				panic(fmt.Errorf("unsupported compiler option: '%s'", opt))
			}
		}
	}

	rootns := NewEmptyNamespace()
	for _, fdp := range req.ProtoFile {
		rootns.Parse(fdp)
		// panic(rootns.PrettyPrint())

		if !fileToGenerate[fdp.GetName()] {
			continue
		}

		syn := SyntaxUnknown
		switch fdp.GetSyntax() {
		case "proto3":
			syn = SyntaxProto3
		case "proto2", "":
			syn = SyntaxProto2
		default:
			panic(fmt.Errorf("unsupported syntax: '%s' in file '%s'", fdp.GetSyntax(), fdp.GetName()))
		}
		if syn == SyntaxProto2 && !allowProto2 {
			panic("proto2 syntax is disabled")
		}

		if filePerEntity {
			writeFiles(syn, fdp, rootns, genService, allowProto2, resp)
		} else {
			f := &ppb.CodeGeneratorResponse_File{}
			fext := filepath.Ext(fdp.GetName())
			fname := strings.TrimSuffix(fdp.GetName(), fext) + "_proto.php"
			f.Name = proto.String(fname)

			b := &bytes.Buffer{}
			w := &writer{b, 0}
			writeFile(syn, w, fdp, rootns, genService, allowProto2)
			f.Content = proto.String(b.String())
			resp.File = append(resp.File, f)
		}
	}
	return resp
}

func customHackNs(fdp *desc.FileDescriptorProto) string {
	fopts := fdp.GetOptions()
	if fopts != nil {
		hns, err := proto.GetExtension(fopts, opt.E_HackNamespace)
		if err != proto.ErrMissingExtension {
			if err != nil {
				panic(fmt.Errorf("GetExtension failed %v %v", err, fopts))
			}
			if hns != nil {
				return *(hns.(*string))
			}
		}
	}
	return ""
}

// For each proto file, it generates multiple .hack files based on how many top level entities the proto file has.
func writeFiles(syn syntax, fdp *desc.FileDescriptorProto, rootNs *Namespace, genService, allowProto2 bool, resp *ppb.CodeGeneratorResponse) {
	// Top level enums.
	for _, edp := range fdp.EnumType {
		writeEntity(edp.Name, fdp, rootNs, resp, func(w *writer, _ *Namespace) {
			writeEnum(w, edp, nil)
		})
	}

	// Messages, recurse.
	for _, dp := range fdp.MessageType {
		writeEntity(dp.Name, fdp, rootNs, resp, func(w *writer, ns *Namespace) {
			writeDescriptor(w, dp, ns, nil, syn)
		})
	}

	// Write services.
	if genService {
		for _, sdp := range fdp.Service {
			writeEntity(sdp.Name, fdp, rootNs, resp, func(w *writer, ns *Namespace) {
				writeService(w, sdp, fdp.GetPackage(), ns)
			})
		}
	}

	// Write file descriptor.
	dsn := "Descriptor"
	writeEntity(&dsn, fdp, rootNs, resp, func(w *writer, _ *Namespace) {
		writeFileDescriptor(w, fdp)
	})
}

func binaryFileDescriptorPath(fdp *desc.FileDescriptorProto) string {
	fext := filepath.Ext(fdp.GetName())
	return strings.TrimSuffix(fdp.GetName(), fext) + "_file_descriptor.pb.bin.gz"
}

func generateBinaryFileDescriptor(fdp *desc.FileDescriptorProto) string {
	// First clear out things we don't need that cause non-determinism.
	fdp.SourceCodeInfo = nil

	bfdp, err := proto.Marshal(fdp)
	if err != nil {
		panic(err)
	}
	var b bytes.Buffer
	gz, err := zlib.NewWriterLevel(&b, flate.BestCompression)
	if err != nil {
		panic(err)
	}
	if _, err = gz.Write(bfdp); err != nil {
		panic(err)
	}
	if err = gz.Close(); err != nil {
		panic(err)
	}

	return b.String()
}

type FileWriter func(*writer, *Namespace)

func writeEntity(name *string, fdp *desc.FileDescriptorProto, rootNs *Namespace, resp *ppb.CodeGeneratorResponse, output FileWriter) {
	f := &ppb.CodeGeneratorResponse_File{}
	fext := filepath.Ext(fdp.GetName())
	fname := strings.TrimSuffix(fdp.GetName(), fext) + "_" + strings.ToLower(*name) + ".hack"
	f.Name = proto.String(fname)
	b := &bytes.Buffer{}
	w := &writer{b, 0}

	packageParts := strings.Split(fdp.GetPackage(), ".")
	ns := rootNs.FindFullyQualifiedNamespace("." + fdp.GetPackage())
	if ns == nil {
		panic("unable to find namespace for: " + fdp.GetPackage())
	}

	// File header.
	// If the file has a custom hack_namespace, use that in generated class,
	// otherwise use the package name as namespace.
	if cns := customHackNs(fdp); cns != "" {
		w.p("namespace %s;", cns)
	} else if fdp.GetPackage() != "" {
		w.p("namespace %s;", strings.Join(packageParts, "\\"))
	}
	w.ln()
	w.p("// Generated by the protocol buffer compiler.  DO NOT EDIT!")
	w.p("// Source: %s", fdp.GetName())
	w.ln()

	output(w, ns)
	f.Content = proto.String(b.String())

	resp.File = append(resp.File, f)
}

// Write everything in a proto file to a generated .php file.
func writeFile(syn syntax, w *writer, fdp *desc.FileDescriptorProto, rootNs *Namespace, genService, allowProto2 bool) {
	packageParts := strings.Split(fdp.GetPackage(), ".")
	ns := rootNs.FindFullyQualifiedNamespace("." + fdp.GetPackage())
	if ns == nil {
		panic("unable to find namespace for: " + fdp.GetPackage())
	}

	// File header.
	w.p("<?hh // strict")
	if cns := customHackNs(fdp); cns != "" {
		w.p("namespace %s;", cns)
	} else if fdp.GetPackage() != "" {
		w.p("namespace %s;", strings.Join(packageParts, "\\"))
	}
	w.ln()
	w.p("// Generated by the protocol buffer compiler.  DO NOT EDIT!")
	w.p("// Source: %s", fdp.GetName())
	w.ln()

	// Top level enums.
	for _, edp := range fdp.EnumType {
		writeEnum(w, edp, nil)
	}

	// Messages, recurse.
	for _, dp := range fdp.MessageType {
		writeDescriptor(w, dp, ns, nil, syn)
	}

	// Write services.
	if genService {
		for _, sdp := range fdp.Service {
			writeService(w, sdp, fdp.GetPackage(), ns)
		}
	}

	// Write file descriptor.
	writeFileDescriptor(w, fdp)
}

func writeFileDescriptor(w *writer, fdp *desc.FileDescriptorProto) {
	w.ln()
	fdClassName := strings.Replace(fdp.GetName(), "/", "_", -1)
	fdClassName = strings.Replace(fdClassName, ".", "__", -1)
	fdClassName = specialPrefix + "FileDescriptor_" + fdClassName
	w.p("class %s implements %s\\FileDescriptor {", fdClassName, libNsInternal)
	w.p("const string NAME = '%s';", fdp.GetName())
	w.p("public function Name(): string {")
	w.p("return self::NAME;")
	w.p("}")
	w.ln()
	w.p("public function FileDescriptorProtoBytes(): string {")
	// This logic matches what protoc-gen-go plugin does. See this SO thread for an example:
	// https://stackoverflow.com/questions/60540511/how-to-display-protoc-gen-go-gzipped-filedescriptorproto-as-plaintext
	binaryProto := generateBinaryFileDescriptor(fdp)
	binaryProtoStr := ""
	for i := 0; i < len(binaryProto); i++ {
		c := binaryProto[i]
		binaryProtoStr += fmt.Sprintf("\\x%x", c)
	}
	w.p("// %d bytes of gzipped FileDescriptorProto as a string", len(binaryProtoStr))
	w.p("return (string)\\gzuncompress(\"%s\");", binaryProtoStr)
	w.p("}")
	w.p("}")
}

func toPhpName(ns, name string) (string, string) {
	return strings.Replace(ns, ".", "\\", -1), strings.Replace(name, ".", "_", -1)
}

func isReservedName(name string) bool {
	lowerName := strings.ToLower(name)
	for _, keyword := range reservedKeywords {
		if lowerName == strings.ToLower(keyword) {
			return true
		}
	}
	return false
}

func escapeReservedName(name string) string {
	if isReservedName(name) {
		return reservedPrefix + name
	}
	return name
}

func escapeReservedConstantName(name string) string {
	if strings.ToLower(name) == "class" {
		return "pb_" + name
	}
	return name
}

type field struct {
	fd                     *desc.FieldDescriptorProto
	typePhpNs, typePhpName string
	typeDescriptor         interface{}
	typeNs                 *Namespace
	// typeEnumDefault        string
	isMap           bool
	oneof           *oneof
	typeFqProtoName string
	syn             syntax
}

func newField(fd *desc.FieldDescriptorProto, ns *Namespace, syn syntax) *field {
	f := &field{
		fd:  fd,
		syn: syn,
	}
	if fd.GetTypeName() != "" {
		typeNs, typeName, i, typeCns := ns.FindFullyQualifiedName(fd.GetTypeName())
		f.typeFqProtoName = typeNs + "." + typeName
		// Use the custom namespace if it exists
		if typeCns != "" {
			f.typePhpNs, f.typePhpName = toPhpName(typeCns, escapeReservedName(typeName))
		} else {
			f.typePhpNs, f.typePhpName = toPhpName(typeNs, escapeReservedName(typeName))
		}
		f.typeDescriptor = i
		f.typeNs = ns.FindFullyQualifiedNamespace(typeNs)
		if dp, ok := f.typeDescriptor.(*desc.DescriptorProto); ok {
			if dp.GetOptions().GetMapEntry() {
				f.isMap = true
			}
		}
		/*if ed, ok := f.typeDescriptor.(*desc.EnumDescriptorProto); ok {
			for _, v := range ed.Value {
				if v.GetNumber() == 0 {
					f.typeEnumDefault = v.GetName()
					break
				}
			}
		}*/
	}

	return f
}

func (f field) mapFields() (*field, *field) {
	dp := f.typeDescriptor.(*desc.DescriptorProto)
	keyField := newField(dp.Field[0], f.typeNs, f.syn)
	valueField := newField(dp.Field[1], f.typeNs, f.syn)
	return keyField, valueField
}

func (f field) isMapWithBoolKey() bool {
	k, _ := f.mapFields()
	return k.fd.GetType() == desc.FieldDescriptorProto_TYPE_BOOL
}

func (f field) phpType() string {
	switch t := f.fd.GetType(); t {
	case desc.FieldDescriptorProto_TYPE_STRING, desc.FieldDescriptorProto_TYPE_BYTES:
		return "string"
	case desc.FieldDescriptorProto_TYPE_INT64,
		desc.FieldDescriptorProto_TYPE_INT32, desc.FieldDescriptorProto_TYPE_UINT64, desc.FieldDescriptorProto_TYPE_UINT32, desc.FieldDescriptorProto_TYPE_SINT64, desc.FieldDescriptorProto_TYPE_SINT32, desc.FieldDescriptorProto_TYPE_FIXED32, desc.FieldDescriptorProto_TYPE_FIXED64, desc.FieldDescriptorProto_TYPE_SFIXED32, desc.FieldDescriptorProto_TYPE_SFIXED64:
		return "int"
	case desc.FieldDescriptorProto_TYPE_FLOAT, desc.FieldDescriptorProto_TYPE_DOUBLE:
		return "float"
	case desc.FieldDescriptorProto_TYPE_BOOL:
		return "bool"
	case desc.FieldDescriptorProto_TYPE_MESSAGE,
		desc.FieldDescriptorProto_TYPE_GROUP:
		return f.typePhpNs + "\\" + f.typePhpName
	case desc.FieldDescriptorProto_TYPE_ENUM:
		return f.typePhpNs + "\\" + f.typePhpName + "_enum_t"
	default:
		panic(fmt.Errorf("unexpected proto type while converting to php type: %v", t))
	}
}

func (f field) defaultValue() string {
	if f.syn == SyntaxProto2 {
		// custom default value.
		if dv := f.fd.GetDefaultValue(); dv != "" {
			// proto2 custom default.
			switch t := f.fd.GetType(); t {
			case desc.FieldDescriptorProto_TYPE_FIXED64, desc.FieldDescriptorProto_TYPE_UINT64:
				u64, err := strconv.ParseUint(dv, 10, 64)
				if err != nil {
					panic(fmt.Errorf("failed to parse custom default uint64 value: %v", err))
				}
				return strconv.FormatInt(int64(u64), 10)
			case desc.FieldDescriptorProto_TYPE_DOUBLE, desc.FieldDescriptorProto_TYPE_FLOAT:
				// Force converting int-like values to float values
				return fmt.Sprintf("(float)%v", dv)
			case desc.FieldDescriptorProto_TYPE_STRING:
				return "'" + strings.Replace(dv, "'", "\\'", -1) + "'"
			case desc.FieldDescriptorProto_TYPE_BYTES:
				// TODO, is this correct unescaping for C escaped values?
				return "\\stripcslashes('" + dv + "')"
			case desc.FieldDescriptorProto_TYPE_ENUM:
				return f.typePhpNs + "\\" + f.typePhpName + "::" + escapeReservedConstantName(dv)
			}
			return dv
		}
		// unlike proto3, this is the first declared value in the protobuf.
		if !f.isRepeated() && f.fd.GetType() == desc.FieldDescriptorProto_TYPE_ENUM {
			ed, ok := f.typeDescriptor.(*desc.EnumDescriptorProto)
			if !ok {
				panic("unable to convert field type descriptor to enum descriptor")
			}
			return f.typePhpNs + "\\" + f.typePhpName + "::" + escapeReservedConstantName(ed.GetValue()[0].GetName())
		}
	}

	if f.isMap {
		return "dict[]"
	}
	if f.isRepeated() {
		return "vec[]"
	}
	switch t := f.fd.GetType(); t {
	case desc.FieldDescriptorProto_TYPE_STRING, desc.FieldDescriptorProto_TYPE_BYTES:
		return "''"
	case desc.FieldDescriptorProto_TYPE_INT64,
		desc.FieldDescriptorProto_TYPE_INT32, desc.FieldDescriptorProto_TYPE_UINT64, desc.FieldDescriptorProto_TYPE_UINT32, desc.FieldDescriptorProto_TYPE_SINT64, desc.FieldDescriptorProto_TYPE_SINT32, desc.FieldDescriptorProto_TYPE_FIXED32, desc.FieldDescriptorProto_TYPE_FIXED64, desc.FieldDescriptorProto_TYPE_SFIXED32, desc.FieldDescriptorProto_TYPE_SFIXED64:
		return "0"
	case desc.FieldDescriptorProto_TYPE_FLOAT, desc.FieldDescriptorProto_TYPE_DOUBLE:
		return "0.0"
	case desc.FieldDescriptorProto_TYPE_BOOL:
		return "false"
	case desc.FieldDescriptorProto_TYPE_ENUM:
		// return f.typePhpNs + "\\" + f.typePhpName + "::" + f.typeEnumDefault
		return f.typePhpNs + "\\" + f.typePhpName + "::FromInt(0)"
	case desc.FieldDescriptorProto_TYPE_MESSAGE,
		desc.FieldDescriptorProto_TYPE_GROUP:
		return "null"
	default:
		panic(fmt.Errorf("unexpected proto type while converting to default value: %v", t))
	}
}

func (f field) isRepeated() bool {
	return f.fd.GetLabel() == desc.FieldDescriptorProto_LABEL_REPEATED
}

func (f field) isMessageOrGroup() bool {
	return f.fd.GetType() == desc.FieldDescriptorProto_TYPE_MESSAGE || f.fd.GetType() == desc.FieldDescriptorProto_TYPE_GROUP
}

func (f field) labeledType() string {
	if f.isMap {
		k, v := f.mapFields()
		kt := k.phpType()
		if f.isMapWithBoolKey() {
			kt = fmt.Sprintf("%s\\bool_map_key_t", libNsInternal)
		}
		return fmt.Sprintf("dict<%s, %s>", kt, v.phpType())
	}
	if f.isRepeated() {
		return "vec<" + f.phpType() + ">"
	}
	if f.isMessageOrGroup() {
		return "?" + f.phpType()
	}
	return f.phpType()
}

func (f field) varName() string {
	return f.fd.GetName()
}

// Default is 0
var writeWireType = map[desc.FieldDescriptorProto_Type]int{
	desc.FieldDescriptorProto_TYPE_FLOAT:    5,
	desc.FieldDescriptorProto_TYPE_DOUBLE:   1,
	desc.FieldDescriptorProto_TYPE_FIXED32:  5,
	desc.FieldDescriptorProto_TYPE_SFIXED32: 5,
	desc.FieldDescriptorProto_TYPE_FIXED64:  1,
	desc.FieldDescriptorProto_TYPE_SFIXED64: 1,
	desc.FieldDescriptorProto_TYPE_STRING:   2,
	desc.FieldDescriptorProto_TYPE_BYTES:    2,
	desc.FieldDescriptorProto_TYPE_MESSAGE:  2,
	desc.FieldDescriptorProto_TYPE_GROUP:    2,
}

var isPackable = map[desc.FieldDescriptorProto_Type]bool{
	desc.FieldDescriptorProto_TYPE_INT64:    true,
	desc.FieldDescriptorProto_TYPE_INT32:    true,
	desc.FieldDescriptorProto_TYPE_UINT64:   true,
	desc.FieldDescriptorProto_TYPE_UINT32:   true,
	desc.FieldDescriptorProto_TYPE_SINT64:   true,
	desc.FieldDescriptorProto_TYPE_SINT32:   true,
	desc.FieldDescriptorProto_TYPE_FLOAT:    true,
	desc.FieldDescriptorProto_TYPE_DOUBLE:   true,
	desc.FieldDescriptorProto_TYPE_FIXED32:  true,
	desc.FieldDescriptorProto_TYPE_SFIXED32: true,
	desc.FieldDescriptorProto_TYPE_FIXED64:  true,
	desc.FieldDescriptorProto_TYPE_SFIXED64: true,
	desc.FieldDescriptorProto_TYPE_BOOL:     true,
	desc.FieldDescriptorProto_TYPE_ENUM:     true,
}

func (f *field) isPacked() bool {
	if f.fd.Options != nil && f.fd.Options.Packed != nil {
		return *f.fd.Options.Packed
	}
	if f.syn == SyntaxProto3 {
		return isPackable[f.fd.GetType()]
	}
	return false
}

func (f *field) writeDecoder(w *writer, dec, wt string) {
	if f.isMap {
		w.p("$obj = new %s();", f.phpType())
		w.p("$obj->MergeFrom(%s->readDecoder());", dec)
		_, vv := f.mapFields()
		k := "$obj->key"
		if vv.isProto2Optional() {
			k = "$obj->getKey()"
		}
		if f.isMapWithBoolKey() {
			k = fmt.Sprintf("%s\\BoolMapKey::FromBool(%s)", libNs, k)
		}
		v := "$obj->value"
		if vv.isProto2Optional() {
			v = "$obj->getValue()"
		}
		if vv.isMessageOrGroup() {
			w.p("$this->%s[%s] = %s ?? new %s();", f.varName(), k, v, vv.phpType())
		} else {
			w.p("$this->%s[%s] = %s;", f.varName(), k, v)
		}
		return
	}
	if f.isMessageOrGroup() {
		// This is different enough we handle it on it's own.
		if f.isRepeated() {
			w.p("$obj = new %s();", f.phpType())
			w.p("$obj->MergeFrom(%s->readDecoder());", dec)
			w.p("$this->%s []= $obj;", f.varName())
		} else {
			if f.isOneofMember() {
				w.p("if ($this->%s->WhichOneof() == %s::%s) {", f.oneof.name, f.oneof.enumTypeName, f.fd.GetName())
				w.p("($this->%s as %s)->%s->MergeFrom(%s->readDecoder());", f.oneof.name, f.oneof.classNameForField(f), f.varName(), dec)
				w.p("} else {")
				w.p("$obj = new %s();", f.phpType())
				w.p("$obj->MergeFrom(%s->readDecoder());", dec)
				w.p("$this->%s = new %s($obj);", f.oneof.name, f.oneof.classNameForField(f))
				w.p("}")
			} else {
				w.p("if ($this->%s is null) {", f.varName())
				w.p("$this->%s = new %s();", f.varName(), f.phpType())
				if f.isOptional() {
					w.p("$this->was_%s_set = true;", f.varName())
				}
				w.p("}")
				w.p("$this->%s->MergeFrom(%s->readDecoder());", f.varName(), dec)
			}
		}
		return
	}

	// TODO should we do wiretype checking here?
	reader := ""
	switch f.fd.GetType() {
	case desc.FieldDescriptorProto_TYPE_STRING,
		desc.FieldDescriptorProto_TYPE_BYTES:
		reader = fmt.Sprintf("%s->readString()", dec)
	case desc.FieldDescriptorProto_TYPE_INT64,
		desc.FieldDescriptorProto_TYPE_UINT64:
		reader = fmt.Sprintf("%s->readVarint()", dec)
	case desc.FieldDescriptorProto_TYPE_INT32:
		reader = fmt.Sprintf("%s->readVarint32Signed()", dec)
	case desc.FieldDescriptorProto_TYPE_UINT32:
		reader = fmt.Sprintf("%s->readVarint32()", dec)
	case desc.FieldDescriptorProto_TYPE_SINT64:
		reader = fmt.Sprintf("%s->readVarintZigZag64()", dec)
	case desc.FieldDescriptorProto_TYPE_SINT32:
		reader = fmt.Sprintf("%s->readVarintZigZag32()", dec)
	case desc.FieldDescriptorProto_TYPE_FLOAT:
		reader = fmt.Sprintf("%s->readFloat()", dec)
	case desc.FieldDescriptorProto_TYPE_DOUBLE:
		reader = fmt.Sprintf("%s->readDouble()", dec)
	case desc.FieldDescriptorProto_TYPE_FIXED32:
		reader = fmt.Sprintf("%s->readLittleEndianInt32Unsigned()", dec)
	case desc.FieldDescriptorProto_TYPE_SFIXED32:
		reader = fmt.Sprintf("%s->readLittleEndianInt32Signed()", dec)
	case desc.FieldDescriptorProto_TYPE_FIXED64,
		desc.FieldDescriptorProto_TYPE_SFIXED64:
		reader = fmt.Sprintf("%s->readLittleEndianInt64()", dec)
	case desc.FieldDescriptorProto_TYPE_BOOL:
		reader = fmt.Sprintf("%s->readBool()", dec)
	case desc.FieldDescriptorProto_TYPE_ENUM:
		reader = fmt.Sprintf("%s\\%s::FromInt(%s->readVarint())", f.typePhpNs, f.typePhpName, dec)
	default:
		panic(fmt.Errorf("unknown reader for fd type: %s", f.fd.GetType()))
	}
	if f.isOneofMember() {
		w.p("$this->%s = new %s(%s);", f.oneof.name, f.oneof.classNameForField(f), reader)
		return
	}
	if !f.isRepeated() {
		w.p("$this->%s = %s;", f.varName(), reader)
		if f.isOptional() {
			w.p("$this->was_%s_set = true;", f.varName())
		}
		return
	}
	// Repeated
	packable := isPackable[f.fd.GetType()]
	if packable {
		w.p("if (%s == 2) {", wt)
		w.p("$packed = %s->readDecoder();", dec)
		w.p("while (!$packed->isEOF()) {")
		w.pdebug("reading packed field")
		packedReader := strings.Replace(reader, dec, "$packed", 1) // Heh, kinda hacky.
		w.p("$this->%s []= %s;", f.varName(), packedReader)
		w.p("}")
		w.p("} else {")
	}
	w.p("$this->%s []= %s;", f.varName(), reader)
	if packable {
		w.p("}")
	}
}

func (f field) primitiveWriters(enc string) (string, string) {
	writer := ""
	switch f.fd.GetType() {
	case desc.FieldDescriptorProto_TYPE_STRING,
		desc.FieldDescriptorProto_TYPE_BYTES:
		writer = fmt.Sprintf("%s->writeString($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_INT64,
		desc.FieldDescriptorProto_TYPE_UINT64,
		desc.FieldDescriptorProto_TYPE_UINT32,
		desc.FieldDescriptorProto_TYPE_INT32:
		writer = fmt.Sprintf("%s->writeVarint($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_SINT64:
		writer = fmt.Sprintf("%s->writeVarintZigZag64($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_SINT32:
		writer = fmt.Sprintf("%s->writeVarintZigZag32($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_FLOAT:
		writer = fmt.Sprintf("%s->writeFloat($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_DOUBLE:
		writer = fmt.Sprintf("%s->writeDouble($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_FIXED32:
		writer = fmt.Sprintf("%s->writeLittleEndianInt32Unsigned($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_SFIXED32:
		writer = fmt.Sprintf("%s->writeLittleEndianInt32Signed($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_FIXED64,
		desc.FieldDescriptorProto_TYPE_SFIXED64:
		writer = fmt.Sprintf("%s->writeLittleEndianInt64($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_BOOL:
		writer = fmt.Sprintf("%s->writeBool($this->%s)", enc, f.varName())
	case desc.FieldDescriptorProto_TYPE_ENUM:
		writer = fmt.Sprintf("%s->writeVarint($this->%s)", enc, f.varName())
	default:
		panic(fmt.Errorf("unknown reader for fd type: %s", f.fd.GetType()))
	}
	tagWriter := fmt.Sprintf("%s->writeTag(%d, %d);", enc, f.fd.GetNumber(), writeWireType[f.fd.GetType()])
	return tagWriter, writer
}

// Oneofs are a subset of all field types, and they serialize their default
// value to the wire.
func (f field) writeEncoderForOneof(w *writer, enc string) {
	if f.isMessageOrGroup() {
		w.p("$nested = new %s\\Encoder();", libNsInternal)
		w.p("$this->%s->WriteTo($nested);", f.fd.GetName())
		w.p("%s->writeEncoder($nested, %d);", enc, f.fd.GetNumber())
		return
	}

	tagWriter, writer := f.primitiveWriters("$e")
	w.p(tagWriter + ";")
	w.p(writer + ";")
}

func (f field) writeEncoder(w *writer, enc string, alwaysEmitDefaultValue bool) {
	if f.isMap {
		w.p("foreach ($this->%s as $k => $v) {", f.varName())
		w.p("$obj = new %s();", f.phpType())
		_, vv := f.mapFields()
		k := "$k"
		if f.isMapWithBoolKey() {
			k = fmt.Sprintf("%s\\BoolMapKey::ToBool($k)", libNs)
		}
		if vv.isProto2Optional() {
			w.p("$obj->setKey(%s);", k)
			w.p("$obj->setValue($v);")
		} else {
			w.p("$obj->key = %s;", k)
			w.p("$obj->value = $v;")
		}
		w.p("$nested = new %s\\Encoder();", libNsInternal)
		w.p("$obj->WriteTo($nested);")
		w.p("%s->writeEncoder($nested, %d);", enc, f.fd.GetNumber())
		w.p("}")
		return
	}

	if f.isMessageOrGroup() {
		// This is different enough we handle it on it's own.
		// TODO we could optimize to not to string copies.
		if f.isRepeated() {
			w.p("foreach ($this->%s as $msg) {", f.varName())
		} else {
			w.p("$msg = $this->%s;", f.varName())
			w.p("if ($msg != null) {")
		}
		if f.isOptional() {
			w.p("if ($this->was_%s_set) {", f.varName())
		}
		w.p("$nested = new %s\\Encoder();", libNsInternal)
		w.p("$msg->WriteTo($nested);")
		w.p("%s->writeEncoder($nested, %d);", enc, f.fd.GetNumber())
		if f.isOptional() {
			w.p("}")
		}
		w.p("}")
		return
	}

	tagWriter, writer := f.primitiveWriters(enc)

	if !f.isRepeated() {
		if f.isOptional() {
			w.p("if ($this->was_%s_set) {", f.varName())
		} else if !alwaysEmitDefaultValue {
			w.p("if ($this->%s !== %s) {", f.varName(), f.defaultValue())
		}
		w.p(tagWriter)
		w.p("%s;", writer)
		if f.isOptional() || !alwaysEmitDefaultValue {
			w.p("}")
		}
		return
	}
	// Repeated
	// Heh, kinda hacky.
	repeatWriter := strings.Replace(writer, "$this->"+f.varName(), "$elem", 1)
	if f.isPacked() {
		// Heh, kinda hacky.
		w.p("if (\\count($this->%s) > 0) {", f.varName())
		packedWriter := strings.Replace(repeatWriter, enc, "$packed", 1)
		w.p("$packed = new %s\\Encoder();", libNsInternal)
		w.p("foreach ($this->%s as $elem) {", f.varName())
		w.pdebug("writing packed")
		w.p("%s;", packedWriter)
		w.p("}")
		w.p("%s->writeEncoder($packed, %d);", enc, f.fd.GetNumber())
		w.p("}")
	} else {
		w.p("foreach ($this->%s as $elem) {", f.varName())
		w.p(tagWriter)
		w.p("%s;", repeatWriter)
		w.p("}")
	}
}

func (f field) writeCopy(w *writer, c string) {
	if f.isMap {
		_, vv := f.mapFields()
		if vv.isMessageOrGroup() {
			w.p("foreach (%s->%s as $k => $v) {", c, f.varName())
			w.p("$nv = new %s();", vv.phpType())
			w.p("$nv->CopyFrom($v);")
			w.p("$this->%s[$k] = $nv;", f.varName())
			w.p("}")
		} else {
			w.p("$this->%s = %s->%s;", f.varName(), c, f.varName())
		}
		return
	}

	if f.isRepeated() && f.isMessageOrGroup() {
		w.p("foreach (%s->%s as $v) {", c, f.varName())
		w.p("$nv = new %s();", f.phpType())
		w.p("$nv->CopyFrom($v);")
		w.p("$this->%s []= $nv;", f.varName())
		w.p("}")
		return
	}

	if f.isMessageOrGroup() {
		w.p("$tmp = %s->%s;", c, f.varName())
		w.p("if ($tmp is nonnull) {")
		w.p("$nv = new %s();", f.phpType())
		w.p("$nv->CopyFrom($tmp);")
		if f.isOptional() {
			camelCaseName := camelCase(f.varName())
			w.p("$this->set%s($nv);", camelCaseName)
			w.p("} else if (%s->has%s()) {", c, camelCaseName)
			w.p("$this->set%s(null);", camelCaseName)
		} else {
			w.p("$this->%s = $nv;", f.varName())
		}
		w.p("}")
	} else {
		if f.isOptional() {
			camelCaseName := camelCase(f.varName())
			w.p("if (%s->has%s()) {", c, camelCaseName)
			w.p("$this->set%s(%s->get%s());", camelCaseName, c, camelCaseName)
			w.p("}")
		} else {
			w.p("$this->%s = %s->%s;", f.varName(), c, f.varName())
		}
	}
}

// https://github.com/google/protobuf/blob/master/src/google/protobuf/struct.proto
// https://github.com/google/protobuf/blob/master/src/google/protobuf/wrappers.proto
func customWriteJson(w *writer, fqn, v string) bool {
	switch fqn {
	// Structs
	case ".google.protobuf.Value":
		w.p("if ($this->kind is \\google\\protobuf\\Value_kind_null_value) {")
		w.p("%s->setCustomEncoding(null);", v)
		w.p("return;")
		w.p("}")
		w.p("if ($this->kind is \\google\\protobuf\\Value_kind_number_value) {")
		w.p("%s->setCustomEncoding($this->kind->number_value);", v)
		w.p("return;")
		w.p("}")
		w.p("if ($this->kind is \\google\\protobuf\\Value_kind_string_value) {")
		w.p("%s->setCustomEncoding($this->kind->string_value);", v)
		w.p("return;")
		w.p("}")
		w.p("if ($this->kind is \\google\\protobuf\\Value_kind_bool_value) {")
		w.p("%s->setCustomEncoding($this->kind->bool_value);", v)
		w.p("return;")
		w.p("}")
		w.p("if ($this->kind is \\google\\protobuf\\Value_kind_list_value) {")
		w.p("%s->setCustomEncoding(%s->encodeMessage($this->kind->list_value));", v, v)
		w.p("return;")
		w.p("}")
		w.p("if ($this->kind is \\google\\protobuf\\Value_kind_struct_value) {")
		w.p("%s->setCustomEncoding(%s->encodeMessage($this->kind->struct_value));", v, v)
		w.p("return;")
		w.p("}")
	case ".google.protobuf.ListValue":
		w.p("$vec = vec[];")
		w.p("foreach ($this->values as $lv) {")
		w.p("$vec []= %s->encodeMessage($lv);", v)
		w.p("}")
		w.p("%s->setCustomEncoding($vec);", v)
	case ".google.protobuf.Struct":
		w.p("$dict = dict[];")
		w.p("foreach ($this->fields as $kk => $vv) {")
		w.p("$dict[$kk]= %s->encodeMessage($vv);", v)
		w.p("}")
		w.p("%s->setCustomEncoding($dict);", v)

	// Wrappers
	case
		".google.protobuf.BoolValue",
		".google.protobuf.StringValue",
		".google.protobuf.DoubleValue",
		".google.protobuf.FloatValue",
		".google.protobuf.Int32Value",
		".google.protobuf.UInt32Value":
		w.p("%s->setCustomEncoding($this->value);", v)
	case ".google.protobuf.Int64Value":
		w.p("%s->setCustomEncoding(\\sprintf('%s', $this->value));", v, "%d")
	case ".google.protobuf.UInt64Value":
		w.p("%s->setCustomEncoding(\\sprintf('%s', $this->value));", v, "%u")
	case ".google.protobuf.BytesValue":
		w.p("%s->setCustomEncoding(%s\\JsonEncoder::encodeBytes($this->value));", v, libNsInternal)
	case ".google.protobuf.Duration":
		w.p("%s->setCustomEncoding(%s\\JsonEncoder::encodeDuration($this->seconds, $this->nanos));", v, libNsInternal)
	default:
		return false
	}
	return true
}

func customMergeJson(w *writer, fqn, v string) bool {
	switch fqn {
	// Structs
	case ".google.protobuf.Value":
		w.p("if (%s === null) {", v)
		w.p("$this->kind = new \\google\\protobuf\\Value_kind_null_value(\\google\\protobuf\\NullValue::NULL_VALUE);")
		w.p("} else if (%s is string) {", v)
		w.p("$this->kind = new \\google\\protobuf\\Value_kind_string_value(%s);", v)
		w.p("} else if (%s is bool) {", v)
		w.p("$this->kind = new \\google\\protobuf\\Value_kind_bool_value(%s);", v)
		w.p("} else if (\\is_numeric(%s)) {", v)
		w.p("$this->kind = new \\google\\protobuf\\Value_kind_number_value((float)%s);", v)
		w.p("} else if (%s is vec<_>) {", v)
		w.p("$lv = new \\google\\protobuf\\ListValue();")
		w.p("$lv->MergeJsonFrom(%s);", v)
		w.p("$this->kind = new \\google\\protobuf\\Value_kind_list_value($lv);")
		w.p("} else if (%s is dict<_,_>) {", v)
		w.p("$struct = new \\google\\protobuf\\Struct();")
		w.p("$struct->MergeJsonFrom(%s);", v)
		w.p("$this->kind = new \\google\\protobuf\\Value_kind_struct_value($struct);")
		w.p("}")
	case ".google.protobuf.ListValue":
		w.p("if (%s is vec<_>) {", v)
		w.p("foreach (%s as $vv) {", v)
		w.p("$val = new \\google\\protobuf\\Value();")
		w.p("$val->MergeJsonFrom($vv);")
		w.p("$this->values []= $val;")
		w.p("}")
		w.p("}")
	case ".google.protobuf.Struct":
		w.p("if (%s is dict<_,_>) {", v)
		w.p("foreach (%s as $k => $vv) {", v)
		w.p("$val = new \\google\\protobuf\\Value();")
		w.p("$val->MergeJsonFrom($vv);")
		w.p("$this->fields[(string)$k] = $val;")
		w.p("}")
		w.p("}")

		// Wrappers
	case ".google.protobuf.BoolValue":
		w.p("$this->value = %s\\JsonDecoder::readBool(%s);", libNsInternal, v)
	case ".google.protobuf.StringValue":
		w.p("$this->value = %s\\JsonDecoder::readString(%s);", libNsInternal, v)
	case ".google.protobuf.BytesValue":
		w.p("$this->value = %s\\JsonDecoder::readBytes(%s);", libNsInternal, v)
	case ".google.protobuf.DoubleValue":
		w.p("$this->value = %s\\JsonDecoder::readFloat(%s);", libNsInternal, v)
	case ".google.protobuf.FloatValue":
		w.p("$this->value = %s\\JsonDecoder::readFloat(%s);", libNsInternal, v)
	case ".google.protobuf.Int64Value":
		w.p("$this->value = %s\\JsonDecoder::readInt64Signed(%s);", libNsInternal, v)
	case ".google.protobuf.UInt64Value":
		w.p("$this->value = %s\\JsonDecoder::readInt64Unsigned(%s);", libNsInternal, v)
	case ".google.protobuf.Int32Value":
		w.p("$this->value = %s\\JsonDecoder::readInt32Signed(%s);", libNsInternal, v)
	case ".google.protobuf.UInt32Value":
		w.p("$this->value = %s\\JsonDecoder::readInt32Unsigned(%s);", libNsInternal, v)
	case ".google.protobuf.Duration":
		w.p("$parts = %s\\JsonDecoder::readDuration(%s);", libNsInternal, v)
		w.p("$this->seconds = $parts[0];")
		w.p("$this->nanos = $parts[1];")
	default:
		return false
	}
	return true
}

func (f *field) jsonReader(v string) string {
	rt := ""
	switch f.fd.GetType() {
	case desc.FieldDescriptorProto_TYPE_STRING:
		rt = "String"
	case desc.FieldDescriptorProto_TYPE_BYTES:
		rt = "Bytes"
	case
		desc.FieldDescriptorProto_TYPE_UINT32:
		rt = "Int32Unsigned"
	case
		desc.FieldDescriptorProto_TYPE_INT32,
		desc.FieldDescriptorProto_TYPE_SINT32,
		desc.FieldDescriptorProto_TYPE_SFIXED32,
		desc.FieldDescriptorProto_TYPE_FIXED32:
		rt = "Int32Signed"
	case
		desc.FieldDescriptorProto_TYPE_INT64,
		desc.FieldDescriptorProto_TYPE_SINT64,
		desc.FieldDescriptorProto_TYPE_SFIXED64:
		rt = "Int64Signed"
	case
		desc.FieldDescriptorProto_TYPE_UINT64,
		desc.FieldDescriptorProto_TYPE_FIXED64:
		rt = "Int64Unsigned"
	case desc.FieldDescriptorProto_TYPE_FLOAT,
		desc.FieldDescriptorProto_TYPE_DOUBLE:
		rt = "Float"
	case desc.FieldDescriptorProto_TYPE_BOOL:
		rt = "Bool"
	case
		desc.FieldDescriptorProto_TYPE_ENUM:
		return fmt.Sprintf("%s\\%s::FromMixed(%s)", f.typePhpNs, f.typePhpName, v)
	default:
		panic(fmt.Errorf("bad json reader: %v", f.fd.GetType()))
	}
	return fmt.Sprintf("%s\\JsonDecoder::read%s(%s)", libNsInternal, rt, v)
}

func (f *field) writeJsonDecoder(w *writer, v string) {
	if f.isMap {
		k, vv := f.mapFields()
		w.p("if (%s !== null) {", v)
		w.p("foreach (%s\\JsonDecoder::readObject(%s) as $k => $v) {", libNsInternal, v)
		kjr := k.jsonReader("$k")
		if f.isMapWithBoolKey() {
			kjr = fmt.Sprintf("%s\\JsonDecoder::readBoolMapKey(%s)", libNsInternal, "$k")
		}

		if vv.isMessageOrGroup() {
			w.p("$obj = new %s();", vv.phpType())
			w.p("$obj->MergeJsonFrom(%s);", v)
			w.p("$this->%s[%s] = $obj;", f.fd.GetName(), kjr)
		} else {
			w.p("$this->%s[%s] = %s;", f.fd.GetName(), kjr, vv.jsonReader("$v"))
			if f.isOptional() {
				w.p("$this->was_%s_set = true;", f.fd.GetName())
			}
		}
		w.p("}")
		w.p("}")
		return
	}

	if f.isMessageOrGroup() {
		if f.isRepeated() {
			w.p("foreach(%s\\JsonDecoder::readList(%s) as $vv) {", libNsInternal, v)
			w.p("$obj = new %s();", f.phpType())
			w.p("$obj->MergeJsonFrom(%s);", "$vv")
			w.p("$this->%s []= $obj;", f.varName())
			w.p("}")
		} else {
			if f.isOneofMember() {
				// TODO: Subtle: technically this doesn't merge, it overwrites!
				w.p("$obj = new %s();", f.phpType())
				w.p("$obj->MergeJsonFrom(%s);", v)
				w.p("$this->%s = new %s($obj);", f.oneof.name, f.oneof.classNameForField(f))
			} else {
				if f.typeFqProtoName != ".google.protobuf.Value" { // A special little snow flake!
					w.p("if ($v is null) break;")
				}
				w.p("if ($this->%s is null) {", f.varName())
				w.p("$this->%s = new %s();", f.varName(), f.phpType())
				if f.isOptional() {
					w.p("$this->was_%s_set = true;", f.varName())
				}
				w.p("}")
				w.p("$this->%s->MergeJsonFrom(%s);", f.varName(), v)
			}
		}
		return
	}

	if f.isRepeated() {
		w.p("foreach(%s\\JsonDecoder::readList(%s) as $vv) {", libNsInternal, v)
		w.p("$this->%s []= %s;", f.varName(), f.jsonReader("$vv"))
		w.p("}")
	} else {
		if f.isOneofMember() {
			// TODO: Subtle: technically this doesn't merge, it overwrites!
			w.p("$this->%s = new %s(%s);", f.oneof.name, f.oneof.classNameForField(f), f.jsonReader(v))
		} else {
			w.p("$this->%s = %s;", f.varName(), f.jsonReader(v))
			if f.isOptional() {
				w.p("$this->was_%s_set = true;", f.varName())
			}
		}
	}
}

func (f field) jsonWriter() (string, string) {
	switch t := f.fd.GetType(); t {
	case desc.FieldDescriptorProto_TYPE_STRING:
		return "String", "Primitive"
	case desc.FieldDescriptorProto_TYPE_BYTES:
		return "Bytes", "Bytes"
	case
		desc.FieldDescriptorProto_TYPE_UINT32,
		desc.FieldDescriptorProto_TYPE_INT32,
		desc.FieldDescriptorProto_TYPE_SINT32,
		desc.FieldDescriptorProto_TYPE_SFIXED32,
		desc.FieldDescriptorProto_TYPE_FIXED32:
		return "Int32", "Primitive"
	case
		desc.FieldDescriptorProto_TYPE_INT64,
		desc.FieldDescriptorProto_TYPE_SINT64,
		desc.FieldDescriptorProto_TYPE_SFIXED64:
		return "Int64Signed", "Int64Signed"
	case
		desc.FieldDescriptorProto_TYPE_UINT64,
		desc.FieldDescriptorProto_TYPE_FIXED64:
		return "Int64Unsigned", "Int64Unsigned"
	case desc.FieldDescriptorProto_TYPE_FLOAT,
		desc.FieldDescriptorProto_TYPE_DOUBLE:
		return "Float", "Float"
	case desc.FieldDescriptorProto_TYPE_BOOL:
		return "Bool", "Primitive"
	case desc.FieldDescriptorProto_TYPE_MESSAGE,
		desc.FieldDescriptorProto_TYPE_GROUP:
		return "Message", "Message"
	case desc.FieldDescriptorProto_TYPE_ENUM:
		return "Enum", "Enum"
	default:
		panic(fmt.Errorf("unexpected proto type: %v", t))
	}
}

func (f field) writeJsonEncoder(w *writer, enc string, forceEmitDefault bool) {
	if f.isMap {
		_, v := f.mapFields()
		_, manyWriter := v.jsonWriter()
		if manyWriter == "Enum" {
			itos := v.typePhpNs + "\\" + v.typePhpName + "::ToStringDict()"
			w.p("%s->writeEnumMap('%s', '%s', %s, $this->%s);", enc, f.fd.GetName(), f.fd.GetJsonName(), itos, f.varName())
		} else {
			w.p("%s->write%sMap('%s', '%s', $this->%s);", enc, manyWriter, f.fd.GetName(), f.fd.GetJsonName(), f.varName())
		}
		return
	}

	writer, manyWriter := f.jsonWriter()

	emitDefault := ", false"
	if forceEmitDefault {
		emitDefault = ", true"
	}
	repeated := ""
	if f.isRepeated() {
		repeated = "List"
		writer = manyWriter
		emitDefault = ""
	}

	if f.isOptional() {
		camelCaseName := camelCase(f.fd.GetName())
		w.p("if ($this->has%s()) {", camelCaseName)
	}
	if writer == "Enum" {
		itos := f.typePhpNs + "\\" + f.typePhpName + "::ToStringDict()"
		w.p("%s->writeEnum%s('%s', '%s', %s, $this->%s%s);", enc, repeated, f.fd.GetName(), f.fd.GetJsonName(), itos, f.varName(), emitDefault)
	} else {
		w.p("%s->write%s%s('%s', '%s', $this->%s%s);", enc, writer, repeated, f.fd.GetName(), f.fd.GetJsonName(), f.varName(), emitDefault)
	}
	if f.isOptional() {
		w.p("}")
	}
}

func (f *field) isOneofMember() bool {
	// optional fields in proto3 are turned into a synthetic oneoff.
	// https://github.com/protocolbuffers/protobuf/blob/main/docs/implementing_proto3_presence.md#updating-a-code-generator
	// We should not process them as oneoff but as their underlying type.
	return f.fd.OneofIndex != nil && !f.isProto3Optional()
}

func (f *field) isProto3Optional() bool {
	return f.fd.Proto3Optional != nil && *f.fd.Proto3Optional
}

func (f *field) isProto2Optional() bool {
	return f.syn == SyntaxProto2 && f.fd.GetLabel() == desc.FieldDescriptorProto_LABEL_OPTIONAL && f.fd.OneofIndex == nil
}

func (f *field) isOptional() bool {
    return f.isProto3Optional() || f.isProto2Optional()
}

// writeEnum writes an enumeration type and constants definitions.
func writeEnum(w *writer, ed *desc.EnumDescriptorProto, prefixNames []string) {
	name := escapeReservedName(strings.Join(append(prefixNames, *ed.Name), "_"))
	typename := name + "_enum_t"
	w.p("newtype %s as int = int;", typename)
	w.p("abstract class %s {", name)
	for _, v := range ed.Value {
		w.p("const %s %s = %d;", typename, escapeReservedConstantName(v.GetName()), *v.Number)
	}

	w.p("private static dict<int, string> $itos = dict[")
	w.i++
	for _, v := range ed.Value {
		w.p("%d => '%s',", v.GetNumber(), v.GetName())
	}
	w.i--
	w.p("];")

	w.p("public static function ToStringDict(): dict<int, string> {")
	w.p("return self::$itos;")
	w.p("}")

	w.p("private static dict<string, int> $stoi = dict[")
	w.i++
	for _, v := range ed.Value {
		w.p("'%s' => %d,", v.GetName(), v.GetNumber())
	}
	w.i--
	w.p("];")

	w.p("public static function FromMixed(mixed $m): %s {", typename)
	w.p("if ($m is string) return idx(self::$stoi, $m, \\is_numeric($m) ? ((int) $m) : 0);")
	w.p("if ($m is int) return $m;")
	w.p("return 0;")
	w.p("}")

	w.p("public static function FromInt(int $i): %s {", typename)
	w.p("return $i;")
	w.p("}")
	w.p("}")
	w.ln()
}

type oneof struct {
	descriptor                                                  *desc.OneofDescriptorProto
	fields                                                      []*field
	name, interfaceName, enumTypeName, classPrefix, notsetClass string
	// v2
}

const notsetEnum = "NOT_SET"

func (o *oneof) classNameForField(f *field) string {
	return o.classPrefix + f.fd.GetName()
}

func writeOneofTypes(w *writer, oo *oneof) {
	// An enum for switching, if hacklang ever supports a type switch, this
	// becomes irrelevant and should be removed.
	w.p("enum %s: int {", oo.enumTypeName)
	w.p("%s = 0;", notsetEnum)
	for _, field := range oo.fields {
		w.p("%s = %d;", field.fd.GetName(), field.fd.GetNumber())
	}
	w.p("}")
	w.ln()

	// The interface.
	w.p("interface %s {", oo.interfaceName)
	w.p("public function WhichOneof(): %s;", oo.enumTypeName)
	w.p("public function WriteTo(%s\\Encoder $e): void;", libNsInternal)
	w.p("public function WriteJsonTo(%s\\JsonEncoder $e): void;", libNsInternal)
	w.p("public function Copy(): %s;", oo.interfaceName)
	w.p("}")
	w.ln()

	// Notset case:
	w.p("class %s implements %s {", oo.notsetClass, oo.interfaceName)
	w.p("public function WhichOneof(): %s {", oo.enumTypeName)
	w.p("return %s::%s;", oo.enumTypeName, notsetEnum)
	w.p("}")
	w.ln()

	w.p("public function WriteTo(%s\\Encoder $e): void {}", libNsInternal)
	w.ln()
	w.p("public function WriteJsonTo(%s\\JsonEncoder $e): void {}", libNsInternal)
	w.ln()
	w.p("public function Copy(): %s { return $this; }", oo.interfaceName)

	w.p("}")
	w.ln()

	// An implementation per field.
	for _, f := range oo.fields {
		w.p("class %s implements %s {", oo.classNameForField(f), oo.interfaceName)
		w.p("public function __construct(public %s $%s) {}", f.phpType(), f.varName())
		w.ln()

		w.p("public function WhichOneof(): %s {", oo.enumTypeName)
		w.p("return %s::%s;", oo.enumTypeName, f.fd.GetName())
		w.p("}")
		w.ln()

		w.p("public function WriteTo(%s\\Encoder $e): void {", libNsInternal)
		f.writeEncoderForOneof(w, "$e")
		w.p("}")
		w.ln()

		w.p("public function WriteJsonTo(%s\\JsonEncoder $e): void {", libNsInternal)
		f.writeJsonEncoder(w, "$e", true)
		w.p("}")
		w.ln()

		w.p("public function Copy(): %s {", oo.interfaceName)
		writeOneofCopy(w, oo, f)
		w.p("}")

		w.p("}")
		w.ln()
	}
}

func writeOneofCopy(w *writer, oo *oneof, f *field) {
	if f.isMap {
		_, vv := f.mapFields()
		if vv.isMessageOrGroup() {
			w.p("$m = [];")
			w.p("foreach($this->%s as $k => $v) {", f.varName())
			w.p("$nv = new %s();", vv.phpType())
			w.p("$nv->CopyFrom($v);")
			w.p("$m[$k] = $nv;")
			w.p("}")
			w.p("return new %s($m);, oo.classNameForField(f)")
		} else {
			w.p("return new %s($this->%s);", oo.classNameForField(f), f.varName())
		}
		return
	}
	if f.isMessageOrGroup() {
		if f.isRepeated() {
			w.p("$a = [];")
			w.p("foreach($this->%s as $v) {", f.varName())
			w.p("$nv = new %s();", f.phpType())
			w.p("$nv->CopyFrom($v);")
			w.p("$a []= $nv;")
			w.p("}")
			w.p("return new %s($a);, oo.classNameForField(f)")

		} else {
			w.p("$nv = new %s();", f.phpType())
			w.p("$nv->CopyFrom($this->%s);", f.varName())
			w.p("return new %s($nv);", oo.classNameForField(f))
		}
		return
	}
	w.p("return new %s($this->%s);", oo.classNameForField(f), f.varName())
}

func isWrapperType(fqn string) bool {
	switch fqn {
	case ".google.protobuf.BoolValue",
		".google.protobuf.StringValue",
		".google.protobuf.BytesValue",
		".google.protobuf.FloatValue",
		".google.protobuf.DoubleValue",
		".google.protobuf.Int32Value",
		".google.protobuf.UInt32Value",
		".google.protobuf.Int64Value",
		".google.protobuf.UInt64Value":
		return true
	}
	return false
}

func camelCase(in string) string {
	words := strings.Split(in, "_")
	camelCaseName := ""
	for _, word := range words {
		if len(word) > 0 {
			camelCaseName += strings.ToUpper(word[:1])
			camelCaseName += word[1:]
		}
	}
	return camelCaseName
}

// https://github.com/golang/protobuf/blob/master/protoc-gen-go/descriptor/descriptor.pb.go
func writeDescriptor(w *writer, dp *desc.DescriptorProto, ns *Namespace, prefixNames []string, syn syntax) {
	nextNames := append(prefixNames, dp.GetName())
	name := escapeReservedName(strings.Join(nextNames, "_"))

	// Nested Enums.
	for _, edp := range dp.EnumType {
		writeEnum(w, edp, nextNames)
	}

	// Wrap fields in our own struct.
	fields := []*field{}
	for _, fd := range dp.Field {
		fields = append(fields, newField(fd, ns, syn))
	}

	// Oneofs: first group each field by it's corresponding oneof.
	oneofFields := map[int32][]*field{}
	for _, field := range fields {
		if !field.isOneofMember() {
			continue
		}
		i := field.fd.GetOneofIndex()
		l := oneofFields[i]
		l = append(l, field)
		oneofFields[i] = l
	}

	// Write oneof types.
	oneofs := []*oneof{}
	for i, od := range dp.OneofDecl {
		// Synthetic one-off for optional in proto3 starts with an underscore.
		// TODO: This may clash with legitimate names starting with an underscore.
		// But this seems like a theoretical issue right now.
		if od.GetName()[0] == '_' {
			continue
		}
		oneofName := strings.Join(append(nextNames, od.GetName()), "_")
		oo := &oneof{
			descriptor:    od,
			name:          od.GetName(),
			fields:        oneofFields[int32(i)],
			interfaceName: oneofName,
			enumTypeName:  oneofName + "_oneof_t",
			classPrefix:   oneofName + "_",
			notsetClass:   oneofName + "_" + "NOT_SET",
		}
		oneofs = append(oneofs, oo)
		writeOneofTypes(w, oo)
	}

	// Now point each field at it's oneof.
	for _, field := range fields {
		if field.isOneofMember() {
			field.oneof = oneofs[field.fd.GetOneofIndex()]
		}
	}

	// Nested Types.
	for _, ndp := range dp.NestedType {
		writeDescriptor(w, ndp, ns, nextNames, syn)
	}

	// w.p("// message %s", dp.GetName())
	w.p("class %s implements %s\\Message {", name, libNs)

	// Members
	for _, f := range fields {
		if f.isOneofMember() {
			continue
		}
		// w.p("// field %s = %d", f.fd.GetName(), f.fd.GetNumber())
		if f.isOptional() {
			// To keep the presence bit in sync, we must switch to
			// an explicit setter and make those fields private.
			// TODO: We should make all usage go through getters/setters.
			w.p("private %s $%s;", f.labeledType(), f.varName())
			w.p("private bool $was_%s_set;", f.varName())
		} else {
			w.p("public %s $%s;", f.labeledType(), f.varName())
		}
	}
	for _, oo := range oneofs {
		w.p("public %s $%s;", oo.interfaceName, oo.name)
	}
	w.p("private string $%sunrecognized;", specialPrefix)
	w.ln()

	// Constructor.
	w.p("public function __construct(shape(")
	w.i++
	for _, f := range fields {
		if f.isOneofMember() {
			continue
		}
		w.p("?'%s' => %s,", f.varName(), f.labeledType())
	}
	for _, oo := range oneofs {
		w.p("?'%s' => %s,", oo.name, oo.interfaceName)
	}
	w.i--
	w.p(") $s = shape()) {")
	for _, f := range fields {
		if f.isOneofMember() {
			continue
		}
		if f.isOptional() {
			varName := f.varName()
			w.p("if (Shapes::keyExists($s, '%s')) {", varName)
			w.p("$this->%s = $s['%s'];", varName, varName)
			w.p("$this->was_%s_set = true;", varName)
			w.p("} else {")
			w.p("$this->%s = %s;", varName, f.defaultValue())
			w.p("$this->was_%s_set = false;", varName)
			w.p("}")
		} else {
			w.p("$this->%s = $s['%s'] ?? %s;", f.varName(), f.varName(), f.defaultValue())
		}
	}
	for _, oo := range oneofs {
		w.p("$this->%s = $s['%s'] ?? new %s();", oo.name, oo.name, oo.notsetClass)
	}
	w.p("$this->%sunrecognized = '';", specialPrefix)
	w.p("}")
	w.ln()

	// Getters, setters & presence checks.
	for _, f := range fields {
		if !f.isOptional() {
			continue
		}
		camelCaseName := camelCase(f.varName())
		w.p("public function get%s(): %s {", camelCaseName, f.labeledType())
		w.p("return $this->%s;", f.varName())
		w.p("}")
		w.ln()

		w.p("public function set%s(%s $v): void {", camelCaseName, f.labeledType())
		w.p("$this->%s = $v;", f.varName())
		w.p("$this->was_%s_set = true;", f.varName())
		w.p("}")
		w.ln()

		w.p("public function has%s(): bool {", camelCaseName)
		w.p("return $this->was_%s_set;", f.varName())
		w.p("}")
		w.ln()
	}

	fqProtoType := ns.Fqn
	fqProtoType += strings.Join(append(prefixNames, dp.GetName()), ".")

	// MessageName().
	w.p("public function MessageName(): string {")
	w.p(`return "%s";`, strings.TrimPrefix(fqProtoType, "."))
	w.p("}")
	w.ln()

	// helper: Create instantiated message from input bytes
	w.p("public static function ParseFrom(string $input): ?%s {", name)
	w.p("$msg = new %s();", name)
	w.p("$e = \\Protobuf\\Unmarshal($input, $msg);")
	w.p("if (!$e->Ok()) {")
	w.p("return null;")
	w.p("}")
	w.p("return $msg;")
	w.p("}")
	w.ln()

	// Now sort the fields by number.
	sort.Slice(fields, func(i, j int) bool {
		return fields[i].fd.GetNumber() < fields[j].fd.GetNumber()
	})

	// MergeFrom function
	w.p("public function MergeFrom(%s\\Decoder $d): void {", libNsInternal)
	w.p("while (!$d->isEOF()){")
	w.p("list($fn, $wt) = $d->readTag();")
	w.p("switch ($fn) {")
	for _, f := range fields {
		w.p("case %d:", f.fd.GetNumber())
		w.i++
		w.pdebug("reading field:%d (%s) wiretype:$wt of %s", f.fd.GetNumber(), f.varName(), dp.GetName())
		f.writeDecoder(w, "$d", "$wt")
		w.pdebug("read field:%d (%s) of %s", f.fd.GetNumber(), f.varName(), dp.GetName())
		w.p("break;")
		w.i--
	}
	w.p("default:")
	w.i++
	w.pdebug("skipping unknown field:$fn wiretype:$wt")
	w.p("$d->skip($fn, $wt);")
	w.i--
	w.p("}") // switch
	w.p("}") // while
	w.p("$this->%sunrecognized = $d->skippedRaw();", specialPrefix)
	w.p("}") // function MergeFrom
	w.ln()

	// WriteTo function
	w.p("public function WriteTo(%s\\Encoder $e): void {", libNsInternal)
	for _, f := range fields {
		if f.isOneofMember() {
			continue
		}
		w.pdebug("maybe writing field:%d (%s) of %s", f.fd.GetNumber(), f.varName(), dp.GetName())
		f.writeEncoder(w, "$e", isWrapperType(fqProtoType))
		w.pdebug("maybe wrote field:%d (%s) of %s", f.fd.GetNumber(), f.varName(), dp.GetName())
	}
	for _, oo := range oneofs {
		w.p("$this->%s->WriteTo($e);", oo.name)
	}
	w.p("$e->writeRaw($this->%sunrecognized);", specialPrefix)
	w.p("}") // WriteToFunction
	w.ln()

	// WriteJsonTo function
	w.p("public function WriteJsonTo(%s\\JsonEncoder $e): void {", libNsInternal)
	if !customWriteJson(w, fqProtoType, "$e") {
		for _, f := range fields {
			if f.isOneofMember() {
				continue
			}
			f.writeJsonEncoder(w, "$e", false)
		}
		for _, oo := range oneofs {
			w.p("$this->%s->WriteJsonTo($e);", oo.name)
		}
	}
	w.p("}") // WriteJsonToFunction
	w.ln()

	// MergeJsonFrom function
	w.p("public function MergeJsonFrom(mixed $m): void {")
	if !customMergeJson(w, fqProtoType, "$m") {
		w.p("if ($m === null) return;")
		w.p("$d = %s\\JsonDecoder::readObject($m);", libNsInternal)
		w.p("foreach ($d as $k => $v) {")
		w.p("switch ($k) {")
		for _, f := range fields {
			ca := fmt.Sprintf("case '%s':", f.fd.GetName())
			if f.fd.GetName() != f.fd.GetJsonName() {
				ca += fmt.Sprintf(" case '%s':", f.fd.GetJsonName())
			}
			w.p(ca)
			w.i++

			f.writeJsonDecoder(w, "$v")
			w.p("break;")
			w.i--
		}
		w.p("default:")
		w.p("break;")
		w.p("}")
		w.p("}")
	}
	w.p("}")
	w.ln()

	// CopyFrom function
	w.p("public function CopyFrom(%s\\Message $o): \\Errors\\Error {", libNs)
	w.p("if (!($o is %s)) {", name)
	w.p("return \\Errors\\Errorf('CopyFrom failed: incorrect type received: %%s', $o->MessageName());")
	w.p("}")
	for _, f := range fields {
		if f.isOneofMember() {
			continue
		}
		f.writeCopy(w, "$o")
	}
	for _, oo := range oneofs {
		w.p("$this->%s = $o->%s->Copy();", oo.name, oo.name)
	}
	w.p("$this->%sunrecognized = $o->%sunrecognized;", specialPrefix, specialPrefix)
	w.p("return \\Errors\\Ok();")
	w.p("}")

	w.p("}") // class
	w.ln()
}

type method struct {
	mdp                                  *desc.MethodDescriptorProto
	PhpName, InputPhpName, OutputPhpName string
}

func newMethod(mdp *desc.MethodDescriptorProto, ns *Namespace) method {
	m := method{mdp: mdp}
	m.PhpName = mdp.GetName()
	tns, tn, _, tCns := ns.FindFullyQualifiedName(mdp.GetInputType())
	// Use custom namespace if it exists
	if tCns != "" {
		tns, tn = toPhpName(tCns, tn)
	} else {
		tns, tn = toPhpName(tns, tn)
	}
	m.InputPhpName = tns + "\\" + tn
	tns, tn, _, tCns = ns.FindFullyQualifiedName(mdp.GetOutputType())
	// Use custom namespace if it exists
	if tCns != "" {
		tns, tn = toPhpName(tCns, tn)
	} else {
		tns, tn = toPhpName(tns, tn)
	}
	m.OutputPhpName = tns + "\\" + tn
	return m
}

func (m method) isStreaming() bool {
	return m.mdp.GetClientStreaming() || m.mdp.GetServerStreaming()
}

func writeService(w *writer, sdp *desc.ServiceDescriptorProto, pkg string, ns *Namespace) {
	methods := []method{}
	for _, mdp := range sdp.Method {
		methods = append(methods, newMethod(mdp, ns))
	}
	fqname := sdp.GetName()
	if pkg != "" {
		fqname = pkg + "." + fqname
	}

	isReflectionApi := fqname == "grpc.reflection.v1alpha.ServerReflection"

	// Client
	w.p("class %sClient {", sdp.GetName())
	w.p("public function __construct(private \\Grpc\\Invoker $invoker) {")
	w.p("}")
	for _, m := range methods {
		if m.isStreaming() {
			continue
		}
		w.ln()
		w.p("public async function %s(\\Grpc\\Context $ctx, %s $in, \\Grpc\\CallOption ...$co): Awaitable<\\Errors\\Result<%s>> {", m.PhpName, m.InputPhpName, m.OutputPhpName)
		w.p("$out = new %s();", m.OutputPhpName)
		w.p("$err = await $this->invoker->Invoke($ctx, '/%s/%s', $in, $out, ...$co);", fqname, m.mdp.GetName())
		w.p("if ($err->Ok()) {")
		w.p("return \\Errors\\ResultV($out);")
		w.p("}")
		w.p("return \\Errors\\ResultE($err);")
		w.p("}")
	}
	w.p("}")
	w.ln()

	// Server
	w.p("interface %sServer {", sdp.GetName())
	for _, m := range methods {
		if m.isStreaming() && !isReflectionApi {
			continue
		}
		w.p("public function %s(\\Grpc\\Context $ctx, %s $in): Awaitable<\\Errors\\Result<%s>>;", m.PhpName, m.InputPhpName, m.OutputPhpName)
	}
	w.p("}")
	w.ln()

	w.p("function %sServiceDescriptor(%sServer $service): \\Grpc\\ServiceDesc {", sdp.GetName(), sdp.GetName())
	w.p("$methods = vec[];")
	for _, m := range methods {
		if m.isStreaming() && !isReflectionApi {
			continue
		}
		w.p("$handler = async (\\Grpc\\Context $ctx, \\Grpc\\Unmarshaller $u): Awaitable<\\Errors\\Result<%s\\Message>> ==> {", libNs)
		w.p("$in = new %s();", m.InputPhpName)
		w.p("$err = $u->Unmarshal($in);")
		w.p("if (!$err->Ok()) {")
		w.p("return \\Errors\\ResultE(\\Errors\\Errorf('proto unmarshal: %s', $err->Error()));", "%s")
		w.p("}")
		w.p("return (await $service->%s($ctx, $in))->As<%s\\Message>();", m.PhpName, libNs)
		w.p("};")
		w.p("$methods []= new \\Grpc\\MethodDesc('%s', $handler);", m.PhpName)
	}
	w.p("return new \\Grpc\\ServiceDesc('%s', $methods);", fqname)
	w.p("}")
	w.ln()

	w.p("function Register%sServer(\\Grpc\\Server $server, %sServer $service): void {", sdp.GetName(), sdp.GetName())
	w.p("$server->RegisterService(%sServiceDescriptor($service));", sdp.GetName())
	w.p("}")
}

// writer is a little helper for output printing. It indents code
// appropriately among other things.
type writer struct {
	w io.Writer
	i int
}

func (w *writer) p(format string, a ...interface{}) {
	if strings.HasPrefix(format, "}") {
		w.i--
	}
	i := w.i
	if i < 0 {
		i = 0
	}
	indent := strings.Repeat("  ", i)
	fmt.Fprintf(w.w, indent+format, a...)
	w.ln()
	if strings.HasSuffix(format, "{") {
		w.i++
	}
}

func (w *writer) ln() {
	fmt.Fprintln(w.w)
}

func (w *writer) pdebug(format string, a ...interface{}) {
	if !genDebug {
		return
	}
	w.p(fmt.Sprintf(`echo "DEBUG: %s\n";`, format), a...)
}
