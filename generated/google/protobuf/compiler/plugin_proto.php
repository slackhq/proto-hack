<?hh // strict
namespace google\protobuf\compiler;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/compiler/plugin.proto

class Version implements \Protobuf\Message {
  public int $major;
  public int $minor;
  public int $patch;
  public string $suffix;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'major' => int,
    ?'minor' => int,
    ?'patch' => int,
    ?'suffix' => string,
  ) $s = shape()) {
    $this->major = $s['major'] ?? 0;
    $this->minor = $s['minor'] ?? 0;
    $this->patch = $s['patch'] ?? 0;
    $this->suffix = $s['suffix'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.compiler.Version";
  }

  public static function ParseFrom(string $input): ?Version {
    $msg = new Version();
    $e = \Protobuf\Unmarshal($input, $msg);
    if (!$e->Ok()) {
      return null;
    }
    return $msg;
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->major = $d->readVarint32Signed();
          break;
        case 2:
          $this->minor = $d->readVarint32Signed();
          break;
        case 3:
          $this->patch = $d->readVarint32Signed();
          break;
        case 4:
          $this->suffix = $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->major !== 0) {
      $e->writeTag(1, 0);
      $e->writeVarint($this->major);
    }
    if ($this->minor !== 0) {
      $e->writeTag(2, 0);
      $e->writeVarint($this->minor);
    }
    if ($this->patch !== 0) {
      $e->writeTag(3, 0);
      $e->writeVarint($this->patch);
    }
    if ($this->suffix !== '') {
      $e->writeTag(4, 2);
      $e->writeString($this->suffix);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeInt32('major', 'major', $this->major, false);
    $e->writeInt32('minor', 'minor', $this->minor, false);
    $e->writeInt32('patch', 'patch', $this->patch, false);
    $e->writeString('suffix', 'suffix', $this->suffix, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'major':
          $this->major = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'minor':
          $this->minor = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'patch':
          $this->patch = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'suffix':
          $this->suffix = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is Version)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->major = $o->major;
    $this->minor = $o->minor;
    $this->patch = $o->patch;
    $this->suffix = $o->suffix;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class CodeGeneratorRequest implements \Protobuf\Message {
  public vec<string> $file_to_generate;
  public string $parameter;
  public vec<\google\protobuf\FileDescriptorProto> $proto_file;
  public ?\google\protobuf\compiler\Version $compiler_version;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'file_to_generate' => vec<string>,
    ?'parameter' => string,
    ?'proto_file' => vec<\google\protobuf\FileDescriptorProto>,
    ?'compiler_version' => ?\google\protobuf\compiler\Version,
  ) $s = shape()) {
    $this->file_to_generate = $s['file_to_generate'] ?? vec[];
    $this->parameter = $s['parameter'] ?? '';
    $this->proto_file = $s['proto_file'] ?? vec[];
    $this->compiler_version = $s['compiler_version'] ?? null;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.compiler.CodeGeneratorRequest";
  }

  public static function ParseFrom(string $input): ?CodeGeneratorRequest {
    $msg = new CodeGeneratorRequest();
    $e = \Protobuf\Unmarshal($input, $msg);
    if (!$e->Ok()) {
      return null;
    }
    return $msg;
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->file_to_generate []= $d->readString();
          break;
        case 2:
          $this->parameter = $d->readString();
          break;
        case 3:
          if ($this->compiler_version == null) $this->compiler_version = new \google\protobuf\compiler\Version();
          $this->compiler_version->MergeFrom($d->readDecoder());
          break;
        case 15:
          $obj = new \google\protobuf\FileDescriptorProto();
          $obj->MergeFrom($d->readDecoder());
          $this->proto_file []= $obj;
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    foreach ($this->file_to_generate as $elem) {
      $e->writeTag(1, 2);
      $e->writeString($elem);
    }
    if ($this->parameter !== '') {
      $e->writeTag(2, 2);
      $e->writeString($this->parameter);
    }
    $msg = $this->compiler_version;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 3);
    }
    foreach ($this->proto_file as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 15);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writePrimitiveList('file_to_generate', 'fileToGenerate', $this->file_to_generate);
    $e->writeString('parameter', 'parameter', $this->parameter, false);
    $e->writeMessage('compiler_version', 'compilerVersion', $this->compiler_version, false);
    $e->writeMessageList('proto_file', 'protoFile', $this->proto_file);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'file_to_generate': case 'fileToGenerate':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $this->file_to_generate []= \Protobuf\Internal\JsonDecoder::readString($vv);
          }
          break;
        case 'parameter':
          $this->parameter = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'compiler_version': case 'compilerVersion':
          if ($v === null) break;
          if ($this->compiler_version == null) $this->compiler_version = new \google\protobuf\compiler\Version();
          $this->compiler_version->MergeJsonFrom($v);
          break;
        case 'proto_file': case 'protoFile':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\FileDescriptorProto();
            $obj->MergeJsonFrom($vv);
            $this->proto_file []= $obj;
          }
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is CodeGeneratorRequest)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->file_to_generate = $o->file_to_generate;
    $this->parameter = $o->parameter;
    $tmp = $o->compiler_version;
    if ($tmp !== null) {
      $nv = new \google\protobuf\compiler\Version();
      $nv->CopyFrom($tmp);
      $this->compiler_version = $nv;
    }
    foreach ($o->proto_file as $v) {
      $nv = new \google\protobuf\FileDescriptorProto();
      $nv->CopyFrom($v);
      $this->proto_file []= $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

newtype CodeGeneratorResponse_Feature_enum_t as int = int;
abstract class CodeGeneratorResponse_Feature {
  const CodeGeneratorResponse_Feature_enum_t FEATURE_NONE = 0;
  const CodeGeneratorResponse_Feature_enum_t FEATURE_PROTO3_OPTIONAL = 1;
  private static dict<int, string> $itos = dict[
    0 => 'FEATURE_NONE',
    1 => 'FEATURE_PROTO3_OPTIONAL',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'FEATURE_NONE' => 0,
    'FEATURE_PROTO3_OPTIONAL' => 1,
  ];
  public static function FromMixed(mixed $m): CodeGeneratorResponse_Feature_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): CodeGeneratorResponse_Feature_enum_t {
    return $i;
  }
}

class CodeGeneratorResponse_File implements \Protobuf\Message {
  public string $name;
  public string $insertion_point;
  public string $content;
  public ?\google\protobuf\GeneratedCodeInfo $generated_code_info;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'name' => string,
    ?'insertion_point' => string,
    ?'content' => string,
    ?'generated_code_info' => ?\google\protobuf\GeneratedCodeInfo,
  ) $s = shape()) {
    $this->name = $s['name'] ?? '';
    $this->insertion_point = $s['insertion_point'] ?? '';
    $this->content = $s['content'] ?? '';
    $this->generated_code_info = $s['generated_code_info'] ?? null;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.compiler.CodeGeneratorResponse.File";
  }

  public static function ParseFrom(string $input): ?CodeGeneratorResponse_File {
    $msg = new CodeGeneratorResponse_File();
    $e = \Protobuf\Unmarshal($input, $msg);
    if (!$e->Ok()) {
      return null;
    }
    return $msg;
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->name = $d->readString();
          break;
        case 2:
          $this->insertion_point = $d->readString();
          break;
        case 15:
          $this->content = $d->readString();
          break;
        case 16:
          if ($this->generated_code_info == null) $this->generated_code_info = new \google\protobuf\GeneratedCodeInfo();
          $this->generated_code_info->MergeFrom($d->readDecoder());
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->name !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->name);
    }
    if ($this->insertion_point !== '') {
      $e->writeTag(2, 2);
      $e->writeString($this->insertion_point);
    }
    if ($this->content !== '') {
      $e->writeTag(15, 2);
      $e->writeString($this->content);
    }
    $msg = $this->generated_code_info;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 16);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('name', 'name', $this->name, false);
    $e->writeString('insertion_point', 'insertionPoint', $this->insertion_point, false);
    $e->writeString('content', 'content', $this->content, false);
    $e->writeMessage('generated_code_info', 'generatedCodeInfo', $this->generated_code_info, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'insertion_point': case 'insertionPoint':
          $this->insertion_point = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'content':
          $this->content = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'generated_code_info': case 'generatedCodeInfo':
          if ($v === null) break;
          if ($this->generated_code_info == null) $this->generated_code_info = new \google\protobuf\GeneratedCodeInfo();
          $this->generated_code_info->MergeJsonFrom($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is CodeGeneratorResponse_File)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->name = $o->name;
    $this->insertion_point = $o->insertion_point;
    $this->content = $o->content;
    $tmp = $o->generated_code_info;
    if ($tmp !== null) {
      $nv = new \google\protobuf\GeneratedCodeInfo();
      $nv->CopyFrom($tmp);
      $this->generated_code_info = $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class CodeGeneratorResponse implements \Protobuf\Message {
  public string $error;
  public int $supported_features;
  public vec<\google\protobuf\compiler\CodeGeneratorResponse_File> $file;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'error' => string,
    ?'supported_features' => int,
    ?'file' => vec<\google\protobuf\compiler\CodeGeneratorResponse_File>,
  ) $s = shape()) {
    $this->error = $s['error'] ?? '';
    $this->supported_features = $s['supported_features'] ?? 0;
    $this->file = $s['file'] ?? vec[];
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.compiler.CodeGeneratorResponse";
  }

  public static function ParseFrom(string $input): ?CodeGeneratorResponse {
    $msg = new CodeGeneratorResponse();
    $e = \Protobuf\Unmarshal($input, $msg);
    if (!$e->Ok()) {
      return null;
    }
    return $msg;
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->error = $d->readString();
          break;
        case 2:
          $this->supported_features = $d->readVarint();
          break;
        case 15:
          $obj = new \google\protobuf\compiler\CodeGeneratorResponse_File();
          $obj->MergeFrom($d->readDecoder());
          $this->file []= $obj;
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->error !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->error);
    }
    if ($this->supported_features !== 0) {
      $e->writeTag(2, 0);
      $e->writeVarint($this->supported_features);
    }
    foreach ($this->file as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 15);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('error', 'error', $this->error, false);
    $e->writeInt64Unsigned('supported_features', 'supportedFeatures', $this->supported_features, false);
    $e->writeMessageList('file', 'file', $this->file);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'error':
          $this->error = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'supported_features': case 'supportedFeatures':
          $this->supported_features = \Protobuf\Internal\JsonDecoder::readInt64Unsigned($v);
          break;
        case 'file':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\compiler\CodeGeneratorResponse_File();
            $obj->MergeJsonFrom($vv);
            $this->file []= $obj;
          }
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is CodeGeneratorResponse)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->error = $o->error;
    $this->supported_features = $o->supported_features;
    foreach ($o->file as $v) {
      $nv = new \google\protobuf\compiler\CodeGeneratorResponse_File();
      $nv->CopyFrom($v);
      $this->file []= $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_compiler_plugin__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/compiler/plugin.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 1967 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\x74\x93\xc1\x6e\xd3\x4c\x10\x80\x7f\xff\x49\xa9\x3c\xad\x1a\x77\x29\x60\x95\x1e\x42\x4\x22\x1c\x70\xa5\xc2\x81\x6b\x5b\x12\xa8\x54\x25\xd1\x2a\x80\xc4\xc5\x72\xed\xb1\x59\xe4\xec\x2c\xeb\x35\x82\xf7\xe0\x45\x78\x34\xde\x0\x79\xbd\x4e\x51\x20\x37\xcf\x37\xb3\xab\x99\x6f\x3d\xf0\xa4\x20\x2a\x4a\x3c\x55\x9a\xc\xdd\xd4\xf9\x69\x4a\x2b\x25\x4a\xd4\xa7\xaa\xac\xb\x21\x23\x9b\x60\x61\x5b\x16\x75\x65\x51\x57\x76\x3c\xdc\xbc\x20\xc3\x2a\xd5\x42\x19\xd2\x6d\xf5\x28\x85\xdd\xf7\xa8\x2b\x41\x92\x1d\xc1\xce\x2a\xf9\x4c\x3a\xf4\x86\xde\x78\x87\xb7\x81\xa5\x42\x92\xe\xff\x77\xb4\x9\x1a\xaa\x12\x93\x7e\xa\x7b\x2d\xb5\x1\xbb\xf\x77\xaa\x3a\xcf\xc5\xb7\xb0\x3f\xf4\xc6\x3e\x77\xd1\xe8\x97\x7\x47\x97\x94\xe1\x1b\x94\xa8\x13\x43\x9a\xe3\x97\x1a\x2b\xc3\xc6\x10\xe4\xa2\xc4\xd8\x50\x5c\xb4\x39\xc\xbd\x61\x6f\xec\xf3\x83\x86\x2f\xc9\x9d\x40\x76\x2\xbe\x4a\x74\xb2\x42\x83\x6d\x2b\x3e\xbf\x5\xec\x12\xc0\x8e\x13\x37\xa7\xc2\xc1\xb0\x37\xde\x3b\x7b\x1c\x6d\x6a\x99\x8a\x12\x5f\xaf\x5\x2c\x1a\xcc\x7d\x9b\x6d\x32\xec\x1a\x82\x4e\x5c\xfc\xb5\x75\x62\xc7\xdb\x3b\x7b\x14\x6d\x33\x1c\x39\x79\x7c\xd0\x11\x7\x46\x3f\x7a\x70\x6f\x63\xe6\x4a\x91\xac\xb0\x71\x87\x5a\x3b\xcf\x3e\x6f\x3\xf6\x1c\x58\x55\x2b\x45\xda\x60\x16\xe7\x98\x98\x5a\x63\x65\x27\xed\xf3\xc3\x75\x66\xea\x12\xec\x2d\xf4\xff\x98\xf5\xe5\xf6\x6\xff\xd9\x83\x55\xc1\xed\xd\xc7\x3f\x3d\xe8\xdb\xf9\x19\xf4\x65\xb2\x42\xd7\x96\xfd\x66\x4f\x61\x20\x64\x85\xda\x8\x92\xb1\x22\x21\x8d\x93\x7f\xb0\xc6\x8b\x86\xb2\x10\x76\x53\x92\x6\xa5\x9\x7\xb6\xa0\xb\x19\x87\xbb\xdd\xdb\x66\x71\x4a\x19\xc6\x42\xe6\x14\x6\xd6\xec\xe8\xaf\xc6\xbb\x17\xcf\x9a\xc6\xaf\x64\x4e\xfc\xb0\xd8\x44\xa3\x57\xb0\xeb\x4c\xb0\x0\xf6\xa7\x93\xf3\xe5\x3b\x3e\x89\x67\xf3\xd9\x24\xf8\x8f\x3d\x84\x7\x1d\x59\xf0\xf9\x72\xfe\x22\x9e\x2f\x96\x57\xf3\xd9\xf9\x75\xe0\x5d\x7c\x80\x93\x94\x56\x5b\x75\x5d\xec\x2f\xec\x66\xd9\x9f\xa3\xfa\xf8\xcc\xd5\x15\x54\x26\xb2\x88\x48\x17\xb7\xab\x64\xbe\x2b\xac\xdc\x22\xaa\x9b\xdf\x1\x0\x0\xff\xff\x42\x72\x31\x23");
  }
}
