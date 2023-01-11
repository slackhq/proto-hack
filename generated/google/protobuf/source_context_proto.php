<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/source_context.proto

class SourceContext implements \Protobuf\Message {
  public string $file_name;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'file_name' => string,
  ) $s = shape()) {
    $this->file_name = $s['file_name'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.SourceContext";
  }

  public static function ParseFrom(string $input): ?SourceContext {
    $msg = new SourceContext();
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
          $this->file_name = $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized .= $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->file_name !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->file_name);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('file_name', 'fileName', $this->file_name, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'file_name': case 'fileName':
          $this->file_name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is SourceContext)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->file_name = $o->file_name;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_source_context__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/source_context.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    return (string)\gzuncompress(\file_get_contents(\realpath(\dirname(__FILE__)) . '/source_context_file_descriptor.pb.bin.gz'));
  }
}
