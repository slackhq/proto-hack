<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/empty.proto

class pb_Empty implements \Protobuf\Message {
  private string $XXX_unrecognized;

  public function __construct(shape(
  ) $s = shape()) {
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Empty";
  }

  public static function ParseFrom(string $input): ?pb_Empty {
    $msg = new pb_Empty();
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
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is pb_Empty)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_empty__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/empty.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 533 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\xe2\x92\x4e\xcf\xcf\x4f\xcf\x49\xd5\x2f\x28\xca\x2f\xc9\x4f\x2a\x4d\xd3\x4f\xcd\x2d\x28\xa9\xd4\x3\x73\x85\xf8\x21\x92\x7a\x30\x49\x25\x76\x2e\x56\x57\x90\xbc\x53\x2d\x97\x70\x72\x7e\xae\x1e\x9a\xbc\x13\x17\x58\x36\x0\xc4\xd\x60\x8c\x82\x49\xa7\xe7\xe7\x24\xe6\xa5\xeb\xe5\x17\xa5\x23\xac\x29\xa9\x2c\x48\x2d\xd6\xcf\xce\xcb\x2f\xcf\x83\x58\x59\x90\xf4\x83\x91\x71\x11\x13\xb3\x7b\x80\xd3\x2a\x26\x39\x77\x88\xce\x0\xa8\x72\xbd\xf0\xd4\x9c\x1c\x6f\x90\xe2\x10\x90\xbe\x24\x36\xb0\x39\xc6\x80\x0\x0\x0\xff\xff\x8d\x77\x45\x30");
  }
}
