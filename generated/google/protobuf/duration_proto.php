<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/duration.proto

class Duration implements \Protobuf\Message {
  public int $seconds;
  public int $nanos;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'seconds' => int,
    ?'nanos' => int,
  ) $s = shape()) {
    $this->seconds = $s['seconds'] ?? 0;
    $this->nanos = $s['nanos'] ?? 0;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Duration";
  }

  public static function ParseFrom(string $input): ?Duration {
    $msg = new Duration();
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
          $this->seconds = $d->readVarint();
          break;
        case 2:
          $this->nanos = $d->readVarint32Signed();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->seconds !== 0) {
      $e->writeTag(1, 0);
      $e->writeVarint($this->seconds);
    }
    if ($this->nanos !== 0) {
      $e->writeTag(2, 0);
      $e->writeVarint($this->nanos);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->setCustomEncoding(\Protobuf\Internal\JsonEncoder::encodeDuration($this->seconds, $this->nanos));
  }

  public function MergeJsonFrom(mixed $m): void {
    $parts = \Protobuf\Internal\JsonDecoder::readDuration($m);
    $this->seconds = $parts[0];
    $this->nanos = $parts[1];
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is Duration)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->seconds = $o->seconds;
    $this->nanos = $o->nanos;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_duration__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/duration.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 697 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\xe2\x92\x4b\xcf\xcf\x4f\xcf\x49\xd5\x2f\x28\xca\x2f\xc9\x4f\x2a\x4d\xd3\x4f\x29\x2d\x4a\x2c\xc9\xcc\xcf\xd3\x3\x8b\x8\xf1\x43\xe4\xf5\x60\xf2\x4a\x56\x5c\x1c\x2e\x50\x25\x42\x12\x5c\xec\xc5\xa9\xc9\xf9\x79\x29\xc5\x12\x8c\xa\x8c\x1a\xcc\x41\x30\xae\x90\x8\x17\x6b\x5e\x62\x5e\x7e\xb1\x4\x93\x2\xa3\x6\x6b\x10\x84\xe3\xd4\xcc\xc8\x25\x9c\x9c\x9f\xab\x87\x66\xa6\x13\x2f\xcc\xc4\x0\x90\x48\x0\x63\x94\x21\x54\x45\x7a\x7e\x4e\x62\x5e\xba\x5e\x7e\x51\x3a\xc2\x81\x25\x95\x5\xa9\xc5\xfa\xd9\x79\xf9\xe5\x79\x70\xc7\x16\x24\xfd\x60\x64\x5c\xc4\xc4\xec\x1e\xe0\xb4\x8a\x49\xce\x1d\xa2\x39\x0\xaa\x43\x2f\x3c\x35\x27\xc7\x1b\xa4\x3e\x4\xa4\x35\x89\xd\x6c\x94\x31\x20\x0\x0\xff\xff\x52\x2f\x56\x5b");
  }
}
