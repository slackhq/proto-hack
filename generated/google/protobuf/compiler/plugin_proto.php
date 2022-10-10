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
    return (string)\gzuncompress(\file_get_contents(\realpath(\dirname(__FILE__)) . '/plugin_file_descriptor.pb.bin.gz'));
  }
}
