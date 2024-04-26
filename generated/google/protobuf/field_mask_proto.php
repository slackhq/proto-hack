<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/field_mask.proto

class FieldMask implements \Protobuf\Message {
  public vec<string> $paths;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'paths' => vec<string>,
  ) $s = shape()) {
    $this->paths = $s['paths'] ?? vec[];
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.FieldMask";
  }

  public static function ParseFrom(string $input): ?FieldMask {
    $msg = new FieldMask();
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
          $this->paths []= $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    foreach ($this->paths as $elem) {
      $e->writeTag(1, 2);
      $e->writeString($elem);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writePrimitiveList('paths', 'paths', $this->paths);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'paths':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $this->paths []= \Protobuf\Internal\JsonDecoder::readString($vv);
          }
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is FieldMask)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->paths = $o->paths;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_field_mask__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/field_mask.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 653 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\xe2\x52\x48\xcf\xcf\x4f\xcf\x49\xd5\x2f\x28\xca\x2f\xc9\x4f\x2a\x4d\xd3\x4f\xcb\x4c\xcd\x49\x89\xcf\x4d\x2c\xce\xd6\x3\x8b\x9\xf1\x43\x54\xe8\xc1\x54\x28\x29\x72\x71\xba\x81\x14\xf9\x26\x16\x67\xb\x89\x70\xb1\x16\x24\x96\x64\x14\x4b\x30\x2a\x30\x6b\x70\x6\x41\x38\x4e\xad\x8c\x5c\xc2\xc9\xf9\xb9\x7a\x68\x5a\x9d\xf8\xe0\x1a\x3\x40\x42\x1\x8c\x51\x46\x50\x25\xe9\xf9\x39\x89\x79\xe9\x7a\xf9\x45\xe9\x8\xa7\x94\x54\x16\xa4\x16\xeb\x67\xe7\xe5\x97\xe7\x41\x9c\x5\x72\x55\x41\xd2\xf\x46\xc6\x45\x4c\xcc\xee\x1\x4e\xab\x98\xe4\xdc\x21\xba\x3\xa0\x5a\xf4\xc2\x53\x73\x72\xbc\x41\x1a\x42\x40\x7a\x93\xd8\xc0\x66\x19\x3\x2\x0\x0\xff\xff\xa3\x37\x50\xc0");
  }
}
