<?hh // strict
namespace baz;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: test/optional_proto3.proto

newtype optional_proto3_InnerEnum_enum_t as int = int;
abstract class optional_proto3_InnerEnum {
  const optional_proto3_InnerEnum_enum_t C = 0;
  const optional_proto3_InnerEnum_enum_t D = 10;
  private static dict<int, string> $itos = dict[
    0 => 'C',
    10 => 'D',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'C' => 0,
    'D' => 10,
  ];
  public static function FromMixed(mixed $m): optional_proto3_InnerEnum_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): optional_proto3_InnerEnum_enum_t {
    return $i;
  }
}

class optional_proto3_InnerMsg implements \Protobuf\Message {
  public string $astring;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'astring' => string,
  ) $s = shape()) {
    $this->astring = $s['astring'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "baz.optional_proto3.InnerMsg";
  }

  public static function ParseFrom(string $input): ?optional_proto3_InnerMsg {
    $msg = new optional_proto3_InnerMsg();
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
          $this->astring = $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->astring !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->astring);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('astring', 'astring', $this->astring, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'astring':
          $this->astring = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is optional_proto3_InnerMsg)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->astring = $o->astring;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class optional_proto3 implements \Protobuf\Message {
  private float $adouble;
  private bool $was_adouble_set;
  private int $aint64;
  private bool $was_aint64_set;
  private bool $abool;
  private bool $was_abool_set;
  private string $astring;
  private bool $was_astring_set;
  private string $abytes;
  private bool $was_abytes_set;
  private \baz\optional_proto3_InnerEnum_enum_t $anenum;
  private bool $was_anenum_set;
  private ?\baz\optional_proto3_InnerMsg $amsg;
  private bool $was_amsg_set;
  private ?\google\protobuf\Any $anany;
  private bool $was_anany_set;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'adouble' => float,
    ?'aint64' => int,
    ?'abool' => bool,
    ?'astring' => string,
    ?'abytes' => string,
    ?'anenum' => \baz\optional_proto3_InnerEnum_enum_t,
    ?'amsg' => ?\baz\optional_proto3_InnerMsg,
    ?'anany' => ?\google\protobuf\Any,
  ) $s = shape()) {
    if (Shapes::keyExists($s, 'adouble')) {
      $this->adouble = $s['adouble'];
      $this->was_adouble_set = true;
    } else {
      $this->adouble = 0.0;
      $this->was_adouble_set = false;
    }
    if (Shapes::keyExists($s, 'aint64')) {
      $this->aint64 = $s['aint64'];
      $this->was_aint64_set = true;
    } else {
      $this->aint64 = 0;
      $this->was_aint64_set = false;
    }
    if (Shapes::keyExists($s, 'abool')) {
      $this->abool = $s['abool'];
      $this->was_abool_set = true;
    } else {
      $this->abool = false;
      $this->was_abool_set = false;
    }
    if (Shapes::keyExists($s, 'astring')) {
      $this->astring = $s['astring'];
      $this->was_astring_set = true;
    } else {
      $this->astring = '';
      $this->was_astring_set = false;
    }
    if (Shapes::keyExists($s, 'abytes')) {
      $this->abytes = $s['abytes'];
      $this->was_abytes_set = true;
    } else {
      $this->abytes = '';
      $this->was_abytes_set = false;
    }
    if (Shapes::keyExists($s, 'anenum')) {
      $this->anenum = $s['anenum'];
      $this->was_anenum_set = true;
    } else {
      $this->anenum = \baz\optional_proto3_InnerEnum::FromInt(0);
      $this->was_anenum_set = false;
    }
    if (Shapes::keyExists($s, 'amsg')) {
      $this->amsg = $s['amsg'];
      $this->was_amsg_set = true;
    } else {
      $this->amsg = null;
      $this->was_amsg_set = false;
    }
    if (Shapes::keyExists($s, 'anany')) {
      $this->anany = $s['anany'];
      $this->was_anany_set = true;
    } else {
      $this->anany = null;
      $this->was_anany_set = false;
    }
    $this->XXX_unrecognized = '';
  }

  public function getAdouble(): float {
    return $this->adouble;
  }

  public function setAdouble(float $v): void {
    $this->adouble = $v;
    $this->was_adouble_set = true;
  }

  public function hasAdouble(): bool {
    return $this->was_adouble_set;
  }

  public function getAint64(): int {
    return $this->aint64;
  }

  public function setAint64(int $v): void {
    $this->aint64 = $v;
    $this->was_aint64_set = true;
  }

  public function hasAint64(): bool {
    return $this->was_aint64_set;
  }

  public function getAbool(): bool {
    return $this->abool;
  }

  public function setAbool(bool $v): void {
    $this->abool = $v;
    $this->was_abool_set = true;
  }

  public function hasAbool(): bool {
    return $this->was_abool_set;
  }

  public function getAstring(): string {
    return $this->astring;
  }

  public function setAstring(string $v): void {
    $this->astring = $v;
    $this->was_astring_set = true;
  }

  public function hasAstring(): bool {
    return $this->was_astring_set;
  }

  public function getAbytes(): string {
    return $this->abytes;
  }

  public function setAbytes(string $v): void {
    $this->abytes = $v;
    $this->was_abytes_set = true;
  }

  public function hasAbytes(): bool {
    return $this->was_abytes_set;
  }

  public function getAnenum(): \baz\optional_proto3_InnerEnum_enum_t {
    return $this->anenum;
  }

  public function setAnenum(\baz\optional_proto3_InnerEnum_enum_t $v): void {
    $this->anenum = $v;
    $this->was_anenum_set = true;
  }

  public function hasAnenum(): bool {
    return $this->was_anenum_set;
  }

  public function getAmsg(): ?\baz\optional_proto3_InnerMsg {
    return $this->amsg;
  }

  public function setAmsg(?\baz\optional_proto3_InnerMsg $v): void {
    $this->amsg = $v;
    $this->was_amsg_set = true;
  }

  public function hasAmsg(): bool {
    return $this->was_amsg_set;
  }

  public function getAnany(): ?\google\protobuf\Any {
    return $this->anany;
  }

  public function setAnany(?\google\protobuf\Any $v): void {
    $this->anany = $v;
    $this->was_anany_set = true;
  }

  public function hasAnany(): bool {
    return $this->was_anany_set;
  }

  public function MessageName(): string {
    return "baz.optional_proto3";
  }

  public static function ParseFrom(string $input): ?optional_proto3 {
    $msg = new optional_proto3();
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
          $this->adouble = $d->readDouble();
          $this->was_adouble_set = true;
          break;
        case 2:
          $this->aint64 = $d->readVarint();
          $this->was_aint64_set = true;
          break;
        case 3:
          $this->abool = $d->readBool();
          $this->was_abool_set = true;
          break;
        case 4:
          $this->astring = $d->readString();
          $this->was_astring_set = true;
          break;
        case 5:
          $this->abytes = $d->readString();
          $this->was_abytes_set = true;
          break;
        case 6:
          $this->anenum = \baz\optional_proto3_InnerEnum::FromInt($d->readVarint());
          $this->was_anenum_set = true;
          break;
        case 7:
          if ($this->amsg is null) {
            $this->amsg = new \baz\optional_proto3_InnerMsg();
            $this->was_amsg_set = true;
          }
          $this->amsg->MergeFrom($d->readDecoder());
          break;
        case 8:
          if ($this->anany is null) {
            $this->anany = new \google\protobuf\Any();
            $this->was_anany_set = true;
          }
          $this->anany->MergeFrom($d->readDecoder());
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->was_adouble_set) {
      $e->writeTag(1, 1);
      $e->writeDouble($this->adouble);
    }
    if ($this->was_aint64_set) {
      $e->writeTag(2, 0);
      $e->writeVarint($this->aint64);
    }
    if ($this->was_abool_set) {
      $e->writeTag(3, 0);
      $e->writeBool($this->abool);
    }
    if ($this->was_astring_set) {
      $e->writeTag(4, 2);
      $e->writeString($this->astring);
    }
    if ($this->was_abytes_set) {
      $e->writeTag(5, 2);
      $e->writeString($this->abytes);
    }
    if ($this->was_anenum_set) {
      $e->writeTag(6, 0);
      $e->writeVarint($this->anenum);
    }
    $msg = $this->amsg;
    if ($msg != null) {
      if ($this->was_amsg_set) {
        $nested = new \Protobuf\Internal\Encoder();
        $msg->WriteTo($nested);
        $e->writeEncoder($nested, 7);
      }
    }
    $msg = $this->anany;
    if ($msg != null) {
      if ($this->was_anany_set) {
        $nested = new \Protobuf\Internal\Encoder();
        $msg->WriteTo($nested);
        $e->writeEncoder($nested, 8);
      }
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    if ($this->hasAdouble()) {
      $e->writeFloat('adouble', 'adouble', $this->adouble, false);
    }
    if ($this->hasAint64()) {
      $e->writeInt64Signed('aint64', 'aint64', $this->aint64, false);
    }
    if ($this->hasAbool()) {
      $e->writeBool('abool', 'abool', $this->abool, false);
    }
    if ($this->hasAstring()) {
      $e->writeString('astring', 'astring', $this->astring, false);
    }
    if ($this->hasAbytes()) {
      $e->writeBytes('abytes', 'abytes', $this->abytes, false);
    }
    if ($this->hasAnenum()) {
      $e->writeEnum('anenum', 'anenum', \baz\optional_proto3_InnerEnum::ToStringDict(), $this->anenum, false);
    }
    if ($this->hasAmsg()) {
      $e->writeMessage('amsg', 'amsg', $this->amsg, false);
    }
    if ($this->hasAnany()) {
      $e->writeMessage('anany', 'anany', $this->anany, false);
    }
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'adouble':
          $this->adouble = \Protobuf\Internal\JsonDecoder::readFloat($v);
          $this->was_adouble_set = true;
          break;
        case 'aint64':
          $this->aint64 = \Protobuf\Internal\JsonDecoder::readInt64Signed($v);
          $this->was_aint64_set = true;
          break;
        case 'abool':
          $this->abool = \Protobuf\Internal\JsonDecoder::readBool($v);
          $this->was_abool_set = true;
          break;
        case 'astring':
          $this->astring = \Protobuf\Internal\JsonDecoder::readString($v);
          $this->was_astring_set = true;
          break;
        case 'abytes':
          $this->abytes = \Protobuf\Internal\JsonDecoder::readBytes($v);
          $this->was_abytes_set = true;
          break;
        case 'anenum':
          $this->anenum = \baz\optional_proto3_InnerEnum::FromMixed($v);
          $this->was_anenum_set = true;
          break;
        case 'amsg':
          if ($v is null) break;
          if ($this->amsg is null) {
            $this->amsg = new \baz\optional_proto3_InnerMsg();
            $this->was_amsg_set = true;
          }
          $this->amsg->MergeJsonFrom($v);
          break;
        case 'anany':
          if ($v is null) break;
          if ($this->anany is null) {
            $this->anany = new \google\protobuf\Any();
            $this->was_anany_set = true;
          }
          $this->anany->MergeJsonFrom($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is optional_proto3)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    if ($o->hasAdouble()) {
      $this->adouble = $o->adouble;
    }
    if ($o->hasAint64()) {
      $this->aint64 = $o->aint64;
    }
    if ($o->hasAbool()) {
      $this->abool = $o->abool;
    }
    if ($o->hasAstring()) {
      $this->astring = $o->astring;
    }
    if ($o->hasAbytes()) {
      $this->abytes = $o->abytes;
    }
    if ($o->hasAnenum()) {
      $this->anenum = $o->anenum;
    }
    $tmp = $o->amsg;
    if ($tmp is nonnull) {
      $nv = new \baz\optional_proto3_InnerMsg();
      $nv->CopyFrom($tmp);
      $this->setAmsg($nv);
    } else if ($o->hasAmsg()) {
      $this->setAmsg(null);
    }
    $tmp = $o->anany;
    if ($tmp is nonnull) {
      $nv = new \google\protobuf\Any();
      $nv->CopyFrom($tmp);
      $this->setAnany($nv);
    } else if ($o->hasAnany()) {
      $this->setAnany(null);
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_test_optional_proto3__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'test/optional_proto3.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 1315 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\x74\x50\xcd\x6a\xe3\x30\x10\xce\xc4\xf1\xdf\xec\xb2\x1b\xcc\x1e\x94\x2c\x29\x26\xf4\xe0\x93\xd\x69\xc9\xa5\xa7\xba\x2d\xa8\x87\x5e\xf4\x2\x46\xa6\xae\x9\x38\x52\x88\xe5\x83\xf3\x4\x79\xcd\xbe\x49\x91\x64\xa7\x10\xe8\x49\x9a\xf9\x7e\x98\xef\xc3\xa5\xaa\x5a\x95\xc9\x83\xda\x49\xc1\x9b\xe2\x70\x94\x4a\xde\xa5\xe6\x89\x9c\x92\x9f\x96\x8b\x5a\xca\xba\xa9\x32\xb3\x2a\xbb\x8f\x8c\x8b\xde\xe2\xeb\x4f\x7\xff\x5e\x29\xa3\x15\xfa\xfc\x5d\x76\x65\x53\x11\x88\x21\x1\x3a\x61\xe3\xe2\xc\x10\xfd\x47\x8f\xef\x84\xda\xde\x93\x69\xc\x89\x43\x81\xd\xb3\x6\x17\xe8\xf2\x52\xca\x86\x38\x31\x24\x1\x9d\x32\x3b\x6a\x48\xdb\xb6\xea\xb8\x13\x35\x99\xc5\x90\x84\xd4\x61\xe3\x62\xb4\x2d\x7b\x55\xb5\xc4\x8d\x21\xf9\x4d\x67\x6c\x98\x35\xf8\x80\x1e\x17\x95\xe8\xf6\xc4\x8b\x21\xf9\xb3\xb9\x49\x4b\x7e\x4a\xaf\x23\xbf\xa\x51\x1d\x5f\x44\xb7\xa7\x2e\x1b\xf8\x5a\xbc\xc5\x19\xdf\xb7\x35\xf1\x63\x48\x7e\x6d\x56\x3f\x4b\xdf\xda\x9a\x7a\xcc\x90\xb5\x2e\x43\x97\xb\x2e\x7a\x12\x18\xe1\xbf\xd4\xd6\x98\x8e\x35\xa6\x8f\xa2\xa7\x3e\xb3\xa4\x33\xc0\xf2\x16\x83\xd1\x27\x22\xdf\x69\x75\x89\xe1\x25\xeb\x7a\x81\xe1\xe5\xd0\xc8\x45\x78\x9a\x4f\xf4\xf3\x3c\xc7\x1c\x31\x28\x86\xa6\xf3\x10\xfd\xc2\xf6\x9a\x7\xe8\x15\xa6\x46\x4b\xb0\x3e\x96\x60\x1a\xb2\x5f\x93\x37\xf7\xd1\x2d\x74\x0\x2b\xd2\x97\x95\x9e\xcd\xf8\x15\x0\x0\xff\xff\x3c\x57\x9b\xb8");
  }
}
