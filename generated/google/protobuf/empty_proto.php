<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/empty.proto

type pb_EmptyFields = shape (
);
class pb_Empty implements \Protobuf\Message {
  private string $XXX_unrecognized;

  public function __construct(shape(
  ) $s = shape()) {
    $this->XXX_unrecognized = '';
  }

  public function setFields(pb_EmptyFields $s = shape()): void {
  }

  public function getNonDefaultFields(): pb_EmptyFields {
    $s = shape();
    return $s;
  }

  public function MessageName(): string {
    return "google.protobuf.Empty";
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
  const string RAW =
  'eNrikk7Pz0/PSdUvKMovyU8qTdNPzS0oqdQDc4X4IZJ6MEkldi5WV5C8Uy2XcHJ+rh6avB'
  .'MXWDYAxA1gjIJJp+fnJOal6+UXpSOsKaksSC3Wz87LL8+DWFmQ9IORcRETs3uA0yomOXeI'
  .'zgCocr3w1Jwcb5DiEJC+JDawOcaAAAAA//+Nd0Uw';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    return (string)\gzuncompress(\base64_decode(self::RAW));
  }
}
