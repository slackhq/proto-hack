<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/any.proto

class Any implements \Protobuf\Message {
  public string $type_url;
  public string $value;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'type_url' => string,
    ?'value' => string,
  ) $s = shape()) {
    $this->type_url = $s['type_url'] ?? '';
    $this->value = $s['value'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Any";
  }

  public static function ParseFrom(string $input): ?Any {
    $msg = new Any();
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
          $this->type_url = $d->readString();
          break;
        case 2:
          $this->value = $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->type_url !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->type_url);
    }
    if ($this->value !== '') {
      $e->writeTag(2, 2);
      $e->writeString($this->value);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('type_url', 'typeUrl', $this->type_url, false);
    $e->writeBytes('value', 'value', $this->value, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'type_url': case 'typeUrl':
          $this->type_url = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'value':
          $this->value = \Protobuf\Internal\JsonDecoder::readBytes($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is Any)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->type_url = $o->type_url;
    $this->value = $o->value;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_any__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/any.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 677 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\xe2\x92\x4c\xcf\xcf\x4f\xcf\x49\xd5\x2f\x28\xca\x2f\xc9\x4f\x2a\x4d\xd3\x4f\xcc\xab\xd4\x3\x73\x84\xf8\x21\x52\x7a\x30\x29\x25\x33\x2e\x66\xc7\xbc\x4a\x21\x49\x2e\x8e\x92\xca\x82\xd4\xf8\xd2\xa2\x1c\x9\x46\x5\x46\xd\xce\x20\x76\x10\x3f\xb4\x28\x47\x48\x84\x8b\xb5\x2c\x31\xa7\x34\x55\x82\x49\x81\x51\x83\x27\x8\xc2\x71\x2a\xe3\x12\x4e\xce\xcf\xd5\x43\x33\xce\x89\xc3\x31\xaf\x32\x0\xc4\x9\x60\x8c\xd2\x81\x4a\xa6\xe7\xe7\x24\xe6\xa5\xeb\xe5\x17\xa5\x23\x5c\x4\x32\xbc\x58\x3f\x3b\x2f\xbf\x3c\xf\xe4\xba\x82\xa4\x45\x4c\xcc\xee\x1\x4e\xab\x98\xe4\xdc\x21\x9a\x2\xa0\x2a\xf5\xc2\x53\x73\x72\xbc\x41\xea\x42\x40\x5a\x92\xd8\xc0\x46\x18\x3\x2\x0\x0\xff\xff\x64\x5f\x4d\x59");
  }
}
