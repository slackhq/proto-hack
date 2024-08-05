package main

import (
	"encoding/json"
	"fmt"
	"regexp"
	"strings"

	desc "github.com/golang/protobuf/protoc-gen-go/descriptor"
)

// Names is a tree of structures and enums defined in a single namespace.
type Names struct {
	parent     *Names
	Children   map[string]*Names
	descriptor interface{} // This should be set on every node of the tree.
}

func newNames(parent *Names) *Names {
	return &Names{
		parent:   parent,
		Children: map[string]*Names{},
	}
}

func (n *Names) get(create bool, parts ...string) *Names {
	if len(parts) < 1 {
		return n
	}
	child := n.Children[parts[0]]
	if child == nil {
		if create {
			child = newNames(n)
			n.Children[parts[0]] = child
		} else {
			return nil
		}
	}
	return child.get(create, parts[1:]...)
}

// Namespace is a tree of namespaces, where each namespace has a tree of Names.
type Namespace struct {
	parent   *Namespace
	Fqn      string
	Names    *Names
	Children map[string]*Namespace
	// fully qualified name prefixed with custom namespace
	cFqn string
}

func NewEmptyNamespace() *Namespace {
	customNs := ""
	return newNamespace(nil, "", &customNs)
}

// Builds a namespace tree with the given fully qualified name and custom fully qualified name.
func newNamespace(parent *Namespace, myName string, customName *string) *Namespace {
	fqn := myName + "."
	cfqn := ""
	if customName != nil {
		cfqn = *customName + "."
	}
	if parent != nil {
		fqn = parent.Fqn + fqn
		cfqn = parent.cFqn + cfqn
	}
	return &Namespace{
		parent:   parent,
		Children: map[string]*Namespace{},
		Names:    newNames(nil),
		Fqn:      fqn,
		cFqn:     cfqn,
	}
}

// Returns the child Namespace corresponding to the given parts.
// If create is true and the child does not exist, it creates the child Namespace.
// If file has custom hack namespace, it also populates cFqn for every field in the tree.
func (n *Namespace) get(create bool, parts []string, customNsParts []string) *Namespace {
	if len(parts) == 0 {
		return n
	}
	child := n.Children[parts[0]]
	if child == nil {
		if create {
			var customName *string
			if len(customNsParts) > 0 {
				customName = &customNsParts[0]
			}
			child = newNamespace(n, parts[0], customName)
			n.Children[parts[0]] = child
		} else {
			return nil
		}
	}
	if len(customNsParts) == 0 {
		return child.get(create, parts[1:], nil)
	}
	return child.get(create, parts[1:], customNsParts[1:])
}

// From any point in the namespace tree, decend to the root and then back up to
// the target namespace.
func (n *Namespace) FindFullyQualifiedNamespace(fqns string) *Namespace {
	if fqns == "" {
		fqns = "." //ugh, hax.
	}
	mustFullyQualified(fqns)
	for n.parent != nil {
		n = n.parent
	}

	if fqns == "." {
		return n
	}

	found := n.get(false, strings.Split(strings.TrimPrefix(fqns, "."), "."), nil)
	if found != nil {
		return found
	}
	panic(fmt.Errorf("unable to find target namespace: %s", fqns))
}

func (n *Namespace) Parse(fdp *desc.FileDescriptorProto) {
	pparts := []string{}
	customNsparts := []string{}
	if fdp.GetPackage() != "" {
		pparts = strings.Split(fdp.GetPackage(), ".")
	}
	// If there is a custom namespace for the file, store the custom fully
	// qualified name i.e. `cFqn` in the namespace tree while building it.
	if cns := customHackNs(fdp); cns != "" {
		if !isValidCustomHackNamespace(cns) {
			panic(fmt.Errorf("Invalid custom hack namespace: %s", cns))
		}
		customNsparts = strings.Split(cns, "\\")
	}
	childns := n.get(true, pparts, customNsparts)

	// Top level enums.
	for _, edp := range fdp.EnumType {
		childns.Names.get(true, *edp.Name).descriptor = edp
	}

	// Messages, recurse.
	for _, dp := range fdp.MessageType {
		childNames := childns.Names.get(true, *dp.Name)
		childNames.descriptor = fdp.MessageType
		childNames.parseDescriptor(dp)
	}
}

func (n *Names) parseDescriptor(dp *desc.DescriptorProto) {

	for _, edp := range dp.EnumType {
		n.get(true, *edp.Name).descriptor = edp
	}

	for _, dp := range dp.NestedType {
		childNames := n.get(true, *dp.Name)
		childNames.descriptor = dp
		childNames.parseDescriptor(dp)
	}
}

func (n *Namespace) PrettyPrint() string {
	b, _ := json.MarshalIndent(n, "", "  ")
	return string(b)
}

func mustFullyQualified(fqn string) {
	if !strings.HasPrefix(fqn, ".") {
		panic("not fully qualified: " + fqn)
	}
}

// Find is where the magic happens. It takes a fully qualified proto name
// e.g. ".foo.bar.baz"
// resolves it to a named entity and returns the proto name split at the
// namespace boundary.
// e.g. ".foo" "bar.baz"
// Also returns the fully qualified name prefixed with custom namespace if
// applicable.
// and also returns the descriptor.
func (n *Namespace) FindFullyQualifiedName(fqn string) (string, string, interface{}, string) {
	mustFullyQualified(fqn)
	ns, name, i, cns := n.find(fqn, true)
	if i == nil {
		panic("couldn't resolve name: " + fqn)
	}
	ns = strings.TrimSuffix(ns, ".")
	cns = strings.TrimSuffix(cns, ".")
	return ns, escapeReservedName(name), i, cns
}

func (n *Namespace) find(fqn string, checkParent bool) (string, string, interface{}, string) {
	if strings.HasPrefix(fqn, n.Fqn) {
		// This name might be in our namespace
		relative := strings.TrimPrefix(fqn, n.Fqn)
		if name := n.Names.get(false, strings.Split(relative, ".")...); name != nil {
			return n.Fqn, relative, name.descriptor, n.cFqn
		}
		// It may also be in a decendant namespace.
		for _, childns := range n.Children {
			rns, rname, i, cns := childns.find(fqn, false)
			if rns != "" {
				return rns, rname, i, cns
			}
		}

	}
	// Try our ancestor namespace.
	// TODO: this will revist n [us] multiple times! We could optimize.
	if checkParent && n.parent != nil {
		return n.parent.FindFullyQualifiedName(fqn)
	}
	return "", "", nil, ""
}

func isValidCustomHackNamespace(s string) bool {
	pattern := `^(\w+\\)*\w+$`
	re := regexp.MustCompile(pattern)
	return re.MatchString(s)
}
