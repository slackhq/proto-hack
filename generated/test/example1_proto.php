<?hh // strict
namespace foo\bar;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: test/example1.proto

newtype AEnum1_enum_t as int = int;
abstract class AEnum1 {
  const AEnum1_enum_t A = 0;
  const AEnum1_enum_t B = 2;
  private static dict<int, string> $itos = dict[
    0 => 'A',
    2 => 'B',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'A' => 0,
    'B' => 2,
  ];
  public static function FromMixed(mixed $m): AEnum1_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): AEnum1_enum_t {
    return $i;
  }
}

class example2 implements \Protobuf\Message {
  public int $aint32;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'aint32' => int,
  ) $s = shape()) {
    $this->aint32 = $s['aint32'] ?? 0;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "foo.bar.example2";
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->aint32 = $d->readVarint32Signed();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->aint32 !== 0) {
      $e->writeTag(1, 0);
      $e->writeVarint($this->aint32);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeInt32('aint32', 'aint32', $this->aint32, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'aint32':
          $this->aint32 = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is example2)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->aint32 = $o->aint32;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

newtype example1_AEnum2_enum_t as int = int;
abstract class example1_AEnum2 {
  const example1_AEnum2_enum_t C = 0;
  const example1_AEnum2_enum_t D = 10;
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
  public static function FromMixed(mixed $m): example1_AEnum2_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): example1_AEnum2_enum_t {
    return $i;
  }
}

enum example1_aoneof_oneof_t: int {
  NOT_SET = 0;
  oostring = 60;
  ooint = 61;
}

interface example1_aoneof {
  public function WhichOneof(): example1_aoneof_oneof_t;
  public function WriteTo(\Protobuf\Internal\Encoder $e): void;
  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void;
  public function Copy(): example1_aoneof;
}

class example1_aoneof_NOT_SET implements example1_aoneof {
  public function WhichOneof(): example1_aoneof_oneof_t {
    return example1_aoneof_oneof_t::NOT_SET;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {}

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {}

  public function Copy(): example1_aoneof { return $this; }
}

class example1_aoneof_oostring implements example1_aoneof {
  public function __construct(public string $oostring) {}

  public function WhichOneof(): example1_aoneof_oneof_t {
    return example1_aoneof_oneof_t::oostring;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(60, 2);;
    $e->writeString($this->oostring);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('oostring', 'oostring', $this->oostring, true);
  }

  public function Copy(): example1_aoneof {
    return new example1_aoneof_oostring($this->oostring);
  }
}

class example1_aoneof_ooint implements example1_aoneof {
  public function __construct(public int $ooint) {}

  public function WhichOneof(): example1_aoneof_oneof_t {
    return example1_aoneof_oneof_t::ooint;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(61, 0);;
    $e->writeVarint($this->ooint);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeInt32('ooint', 'ooint', $this->ooint, true);
  }

  public function Copy(): example1_aoneof {
    return new example1_aoneof_ooint($this->ooint);
  }
}

class example1_example2 implements \Protobuf\Message {
  public string $astring;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'astring' => string,
  ) $s = shape()) {
    $this->astring = $s['astring'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "foo.bar.example1.example2";
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
    if (!($o is example1_example2)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->astring = $o->astring;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class example1_AmapEntry implements \Protobuf\Message {
  public string $key;
  public string $value;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'key' => string,
    ?'value' => string,
  ) $s = shape()) {
    $this->key = $s['key'] ?? '';
    $this->value = $s['value'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "foo.bar.example1.AmapEntry";
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->key = $d->readString();
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
    if ($this->key !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->key);
    }
    if ($this->value !== '') {
      $e->writeTag(2, 2);
      $e->writeString($this->value);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('key', 'key', $this->key, false);
    $e->writeString('value', 'value', $this->value, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'key':
          $this->key = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'value':
          $this->value = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is example1_AmapEntry)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->key = $o->key;
    $this->value = $o->value;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class example1_Amap2Entry implements \Protobuf\Message {
  public string $key;
  public ?\fiz\baz\example2 $value;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'key' => string,
    ?'value' => ?\fiz\baz\example2,
  ) $s = shape()) {
    $this->key = $s['key'] ?? '';
    $this->value = $s['value'] ?? null;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "foo.bar.example1.Amap2Entry";
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->key = $d->readString();
          break;
        case 2:
          if ($this->value == null) $this->value = new \fiz\baz\example2();
          $this->value->MergeFrom($d->readDecoder());
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->key !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->key);
    }
    $msg = $this->value;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 2);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('key', 'key', $this->key, false);
    $e->writeMessage('value', 'value', $this->value, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'key':
          $this->key = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'value':
          if ($v === null) break;
          if ($this->value == null) $this->value = new \fiz\baz\example2();
          $this->value->MergeJsonFrom($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is example1_Amap2Entry)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->key = $o->key;
    $tmp = $o->value;
    if ($tmp !== null) {
      $nv = new \fiz\baz\example2();
      $nv->CopyFrom($tmp);
      $this->value = $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class example1 implements \Protobuf\Message {
  public float $adouble;
  public float $afloat;
  public int $aint32;
  public int $aint64;
  public int $auint32;
  public int $auint64;
  public int $asint32;
  public int $asint64;
  public int $afixed32;
  public int $afixed64;
  public int $asfixed32;
  public int $asfixed64;
  public bool $abool;
  public string $astring;
  public string $abytes;
  public \foo\bar\AEnum1_enum_t $aenum1;
  public \foo\bar\example1_AEnum2_enum_t $aenum2;
  public \fiz\baz\AEnum2_enum_t $aenum22;
  public vec<string> $manystring;
  public vec<int> $manyint64;
  public ?\foo\bar\example1_example2 $aexample2;
  public ?\foo\bar\example2 $aexample22;
  public ?\fiz\baz\example2 $aexample23;
  public dict<string, string> $amap;
  public dict<string, \fiz\baz\example2> $amap2;
  public int $outoforder;
  public ?\google\protobuf\Any $anany;
  public example1_aoneof $aoneof;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'adouble' => float,
    ?'afloat' => float,
    ?'aint32' => int,
    ?'aint64' => int,
    ?'auint32' => int,
    ?'auint64' => int,
    ?'asint32' => int,
    ?'asint64' => int,
    ?'afixed32' => int,
    ?'afixed64' => int,
    ?'asfixed32' => int,
    ?'asfixed64' => int,
    ?'abool' => bool,
    ?'astring' => string,
    ?'abytes' => string,
    ?'aenum1' => \foo\bar\AEnum1_enum_t,
    ?'aenum2' => \foo\bar\example1_AEnum2_enum_t,
    ?'aenum22' => \fiz\baz\AEnum2_enum_t,
    ?'manystring' => vec<string>,
    ?'manyint64' => vec<int>,
    ?'aexample2' => ?\foo\bar\example1_example2,
    ?'aexample22' => ?\foo\bar\example2,
    ?'aexample23' => ?\fiz\baz\example2,
    ?'amap' => dict<string, string>,
    ?'amap2' => dict<string, \fiz\baz\example2>,
    ?'outoforder' => int,
    ?'anany' => ?\google\protobuf\Any,
    ?'aoneof' => example1_aoneof,
  ) $s = shape()) {
    $this->adouble = $s['adouble'] ?? 0.0;
    $this->afloat = $s['afloat'] ?? 0.0;
    $this->aint32 = $s['aint32'] ?? 0;
    $this->aint64 = $s['aint64'] ?? 0;
    $this->auint32 = $s['auint32'] ?? 0;
    $this->auint64 = $s['auint64'] ?? 0;
    $this->asint32 = $s['asint32'] ?? 0;
    $this->asint64 = $s['asint64'] ?? 0;
    $this->afixed32 = $s['afixed32'] ?? 0;
    $this->afixed64 = $s['afixed64'] ?? 0;
    $this->asfixed32 = $s['asfixed32'] ?? 0;
    $this->asfixed64 = $s['asfixed64'] ?? 0;
    $this->abool = $s['abool'] ?? false;
    $this->astring = $s['astring'] ?? '';
    $this->abytes = $s['abytes'] ?? '';
    $this->aenum1 = $s['aenum1'] ?? \foo\bar\AEnum1::FromInt(0);
    $this->aenum2 = $s['aenum2'] ?? \foo\bar\example1_AEnum2::FromInt(0);
    $this->aenum22 = $s['aenum22'] ?? \fiz\baz\AEnum2::FromInt(0);
    $this->manystring = $s['manystring'] ?? vec[];
    $this->manyint64 = $s['manyint64'] ?? vec[];
    $this->aexample2 = $s['aexample2'] ?? null;
    $this->aexample22 = $s['aexample22'] ?? null;
    $this->aexample23 = $s['aexample23'] ?? null;
    $this->amap = $s['amap'] ?? dict[];
    $this->amap2 = $s['amap2'] ?? dict[];
    $this->outoforder = $s['outoforder'] ?? 0;
    $this->anany = $s['anany'] ?? null;
    $this->aoneof = $s['aoneof'] ?? new example1_aoneof_NOT_SET();
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "foo.bar.example1";
  }

  public function MergeFrom(\Protobuf\Internal\Decoder $d): void {
    while (!$d->isEOF()){
      list($fn, $wt) = $d->readTag();
      switch ($fn) {
        case 1:
          $this->adouble = $d->readDouble();
          break;
        case 2:
          $this->afloat = $d->readFloat();
          break;
        case 3:
          $this->aint32 = $d->readVarint32Signed();
          break;
        case 4:
          $this->aint64 = $d->readVarint();
          break;
        case 5:
          $this->auint32 = $d->readVarint32();
          break;
        case 6:
          $this->auint64 = $d->readVarint();
          break;
        case 7:
          $this->asint32 = $d->readVarintZigZag32();
          break;
        case 8:
          $this->asint64 = $d->readVarintZigZag64();
          break;
        case 9:
          $this->afixed32 = $d->readLittleEndianInt32Unsigned();
          break;
        case 10:
          $this->afixed64 = $d->readLittleEndianInt64();
          break;
        case 11:
          $this->asfixed32 = $d->readLittleEndianInt32Signed();
          break;
        case 12:
          $this->asfixed64 = $d->readLittleEndianInt64();
          break;
        case 13:
          $this->abool = $d->readBool();
          break;
        case 14:
          $this->astring = $d->readString();
          break;
        case 15:
          $this->abytes = $d->readString();
          break;
        case 20:
          $this->aenum1 = \foo\bar\AEnum1::FromInt($d->readVarint());
          break;
        case 21:
          $this->aenum2 = \foo\bar\example1_AEnum2::FromInt($d->readVarint());
          break;
        case 22:
          $this->aenum22 = \fiz\baz\AEnum2::FromInt($d->readVarint());
          break;
        case 30:
          $this->manystring []= $d->readString();
          break;
        case 31:
          if ($wt == 2) {
            $packed = $d->readDecoder();
            while (!$packed->isEOF()) {
              $this->manyint64 []= $packed->readVarint();
            }
          } else {
            $this->manyint64 []= $d->readVarint();
          }
          break;
        case 40:
          if ($this->aexample2 == null) $this->aexample2 = new \foo\bar\example1_example2();
          $this->aexample2->MergeFrom($d->readDecoder());
          break;
        case 41:
          if ($this->aexample22 == null) $this->aexample22 = new \foo\bar\example2();
          $this->aexample22->MergeFrom($d->readDecoder());
          break;
        case 42:
          if ($this->aexample23 == null) $this->aexample23 = new \fiz\baz\example2();
          $this->aexample23->MergeFrom($d->readDecoder());
          break;
        case 49:
          $this->outoforder = $d->readVarint();
          break;
        case 51:
          $obj = new \foo\bar\example1_AmapEntry();
          $obj->MergeFrom($d->readDecoder());
          $this->amap[$obj->key] = $obj->value;
          break;
        case 52:
          $obj = new \foo\bar\example1_Amap2Entry();
          $obj->MergeFrom($d->readDecoder());
          $this->amap2[$obj->key] = $obj->value ?? new \fiz\baz\example2();
          break;
        case 60:
          $this->aoneof = new example1_aoneof_oostring($d->readString());
          break;
        case 61:
          $this->aoneof = new example1_aoneof_ooint($d->readVarint32Signed());
          break;
        case 80:
          if ($this->anany == null) $this->anany = new \google\protobuf\Any();
          $this->anany->MergeFrom($d->readDecoder());
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->adouble !== 0.0) {
      $e->writeTag(1, 1);
      $e->writeDouble($this->adouble);
    }
    if ($this->afloat !== 0.0) {
      $e->writeTag(2, 5);
      $e->writeFloat($this->afloat);
    }
    if ($this->aint32 !== 0) {
      $e->writeTag(3, 0);
      $e->writeVarint($this->aint32);
    }
    if ($this->aint64 !== 0) {
      $e->writeTag(4, 0);
      $e->writeVarint($this->aint64);
    }
    if ($this->auint32 !== 0) {
      $e->writeTag(5, 0);
      $e->writeVarint($this->auint32);
    }
    if ($this->auint64 !== 0) {
      $e->writeTag(6, 0);
      $e->writeVarint($this->auint64);
    }
    if ($this->asint32 !== 0) {
      $e->writeTag(7, 0);
      $e->writeVarintZigZag32($this->asint32);
    }
    if ($this->asint64 !== 0) {
      $e->writeTag(8, 0);
      $e->writeVarintZigZag64($this->asint64);
    }
    if ($this->afixed32 !== 0) {
      $e->writeTag(9, 5);
      $e->writeLittleEndianInt32Unsigned($this->afixed32);
    }
    if ($this->afixed64 !== 0) {
      $e->writeTag(10, 1);
      $e->writeLittleEndianInt64($this->afixed64);
    }
    if ($this->asfixed32 !== 0) {
      $e->writeTag(11, 5);
      $e->writeLittleEndianInt32Signed($this->asfixed32);
    }
    if ($this->asfixed64 !== 0) {
      $e->writeTag(12, 1);
      $e->writeLittleEndianInt64($this->asfixed64);
    }
    if ($this->abool !== false) {
      $e->writeTag(13, 0);
      $e->writeBool($this->abool);
    }
    if ($this->astring !== '') {
      $e->writeTag(14, 2);
      $e->writeString($this->astring);
    }
    if ($this->abytes !== '') {
      $e->writeTag(15, 2);
      $e->writeString($this->abytes);
    }
    if ($this->aenum1 !== \foo\bar\AEnum1::FromInt(0)) {
      $e->writeTag(20, 0);
      $e->writeVarint($this->aenum1);
    }
    if ($this->aenum2 !== \foo\bar\example1_AEnum2::FromInt(0)) {
      $e->writeTag(21, 0);
      $e->writeVarint($this->aenum2);
    }
    if ($this->aenum22 !== \fiz\baz\AEnum2::FromInt(0)) {
      $e->writeTag(22, 0);
      $e->writeVarint($this->aenum22);
    }
    foreach ($this->manystring as $elem) {
      $e->writeTag(30, 2);
      $e->writeString($elem);
    }
    $packed = new \Protobuf\Internal\Encoder();
    foreach ($this->manyint64 as $elem) {
      $packed->writeVarint($elem);
    }
    $e->writeEncoder($packed, 31);
    $msg = $this->aexample2;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 40);
    }
    $msg = $this->aexample22;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 41);
    }
    $msg = $this->aexample23;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 42);
    }
    if ($this->outoforder !== 0) {
      $e->writeTag(49, 0);
      $e->writeVarint($this->outoforder);
    }
    foreach ($this->amap as $k => $v) {
      $obj = new \foo\bar\example1_AmapEntry();
      $obj->key = $k;
      $obj->value = $v;
      $nested = new \Protobuf\Internal\Encoder();
      $obj->WriteTo($nested);
      $e->writeEncoder($nested, 51);
    }
    foreach ($this->amap2 as $k => $v) {
      $obj = new \foo\bar\example1_Amap2Entry();
      $obj->key = $k;
      $obj->value = $v;
      $nested = new \Protobuf\Internal\Encoder();
      $obj->WriteTo($nested);
      $e->writeEncoder($nested, 52);
    }
    $msg = $this->anany;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 80);
    }
    $this->aoneof->WriteTo($e);
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeFloat('adouble', 'adouble', $this->adouble, false);
    $e->writeFloat('afloat', 'afloat', $this->afloat, false);
    $e->writeInt32('aint32', 'aint32', $this->aint32, false);
    $e->writeInt64Signed('aint64', 'aint64', $this->aint64, false);
    $e->writeInt32('auint32', 'auint32', $this->auint32, false);
    $e->writeInt64Unsigned('auint64', 'auint64', $this->auint64, false);
    $e->writeInt32('asint32', 'asint32', $this->asint32, false);
    $e->writeInt64Signed('asint64', 'asint64', $this->asint64, false);
    $e->writeInt32('afixed32', 'afixed32', $this->afixed32, false);
    $e->writeInt64Unsigned('afixed64', 'afixed64', $this->afixed64, false);
    $e->writeInt32('asfixed32', 'asfixed32', $this->asfixed32, false);
    $e->writeInt64Signed('asfixed64', 'asfixed64', $this->asfixed64, false);
    $e->writeBool('abool', 'abool', $this->abool, false);
    $e->writeString('astring', 'astring', $this->astring, false);
    $e->writeBytes('abytes', 'abytes', $this->abytes, false);
    $e->writeEnum('aenum1', 'aenum1', \foo\bar\AEnum1::ToStringDict(), $this->aenum1, false);
    $e->writeEnum('aenum2', 'aenum2', \foo\bar\example1_AEnum2::ToStringDict(), $this->aenum2, false);
    $e->writeEnum('aenum22', 'aenum22', \fiz\baz\AEnum2::ToStringDict(), $this->aenum22, false);
    $e->writePrimitiveList('manystring', 'manystring', $this->manystring);
    $e->writeInt64SignedList('manyint64', 'manyint64', $this->manyint64);
    $e->writeMessage('aexample2', 'aexample2', $this->aexample2, false);
    $e->writeMessage('aexample22', 'aexample22', $this->aexample22, false);
    $e->writeMessage('aexample23', 'aexample23', $this->aexample23, false);
    $e->writeInt64Signed('outoforder', 'outoforder', $this->outoforder, false);
    $e->writePrimitiveMap('amap', 'amap', $this->amap);
    $e->writeMessageMap('amap2', 'amap2', $this->amap2);
    $e->writeMessage('anany', 'anany', $this->anany, false);
    $this->aoneof->WriteJsonTo($e);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'adouble':
          $this->adouble = \Protobuf\Internal\JsonDecoder::readFloat($v);
          break;
        case 'afloat':
          $this->afloat = \Protobuf\Internal\JsonDecoder::readFloat($v);
          break;
        case 'aint32':
          $this->aint32 = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'aint64':
          $this->aint64 = \Protobuf\Internal\JsonDecoder::readInt64Signed($v);
          break;
        case 'auint32':
          $this->auint32 = \Protobuf\Internal\JsonDecoder::readInt32Unsigned($v);
          break;
        case 'auint64':
          $this->auint64 = \Protobuf\Internal\JsonDecoder::readInt64Unsigned($v);
          break;
        case 'asint32':
          $this->asint32 = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'asint64':
          $this->asint64 = \Protobuf\Internal\JsonDecoder::readInt64Signed($v);
          break;
        case 'afixed32':
          $this->afixed32 = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'afixed64':
          $this->afixed64 = \Protobuf\Internal\JsonDecoder::readInt64Unsigned($v);
          break;
        case 'asfixed32':
          $this->asfixed32 = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'asfixed64':
          $this->asfixed64 = \Protobuf\Internal\JsonDecoder::readInt64Signed($v);
          break;
        case 'abool':
          $this->abool = \Protobuf\Internal\JsonDecoder::readBool($v);
          break;
        case 'astring':
          $this->astring = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'abytes':
          $this->abytes = \Protobuf\Internal\JsonDecoder::readBytes($v);
          break;
        case 'aenum1':
          $this->aenum1 = \foo\bar\AEnum1::FromMixed($v);
          break;
        case 'aenum2':
          $this->aenum2 = \foo\bar\example1_AEnum2::FromMixed($v);
          break;
        case 'aenum22':
          $this->aenum22 = \fiz\baz\AEnum2::FromMixed($v);
          break;
        case 'manystring':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $this->manystring []= \Protobuf\Internal\JsonDecoder::readString($vv);
          }
          break;
        case 'manyint64':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $this->manyint64 []= \Protobuf\Internal\JsonDecoder::readInt64Signed($vv);
          }
          break;
        case 'aexample2':
          if ($v === null) break;
          if ($this->aexample2 == null) $this->aexample2 = new \foo\bar\example1_example2();
          $this->aexample2->MergeJsonFrom($v);
          break;
        case 'aexample22':
          if ($v === null) break;
          if ($this->aexample22 == null) $this->aexample22 = new \foo\bar\example2();
          $this->aexample22->MergeJsonFrom($v);
          break;
        case 'aexample23':
          if ($v === null) break;
          if ($this->aexample23 == null) $this->aexample23 = new \fiz\baz\example2();
          $this->aexample23->MergeJsonFrom($v);
          break;
        case 'outoforder':
          $this->outoforder = \Protobuf\Internal\JsonDecoder::readInt64Signed($v);
          break;
        case 'amap':
          if ($v !== null) {
            foreach (\Protobuf\Internal\JsonDecoder::readObject($v) as $k => $v) {
              $this->amap[\Protobuf\Internal\JsonDecoder::readString($k)] = \Protobuf\Internal\JsonDecoder::readString($v);
            }
          }
          break;
        case 'amap2':
          if ($v !== null) {
            foreach (\Protobuf\Internal\JsonDecoder::readObject($v) as $k => $v) {
              $obj = new \fiz\baz\example2();
              $obj->MergeJsonFrom($v);
              $this->amap2[\Protobuf\Internal\JsonDecoder::readString($k)] = $obj;
            }
          }
          break;
        case 'oostring':
          $this->aoneof = new example1_aoneof_oostring(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'ooint':
          $this->aoneof = new example1_aoneof_ooint(\Protobuf\Internal\JsonDecoder::readInt32Signed($v));
          break;
        case 'anany':
          if ($v === null) break;
          if ($this->anany == null) $this->anany = new \google\protobuf\Any();
          $this->anany->MergeJsonFrom($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is example1)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->adouble = $o->adouble;
    $this->afloat = $o->afloat;
    $this->aint32 = $o->aint32;
    $this->aint64 = $o->aint64;
    $this->auint32 = $o->auint32;
    $this->auint64 = $o->auint64;
    $this->asint32 = $o->asint32;
    $this->asint64 = $o->asint64;
    $this->afixed32 = $o->afixed32;
    $this->afixed64 = $o->afixed64;
    $this->asfixed32 = $o->asfixed32;
    $this->asfixed64 = $o->asfixed64;
    $this->abool = $o->abool;
    $this->astring = $o->astring;
    $this->abytes = $o->abytes;
    $this->aenum1 = $o->aenum1;
    $this->aenum2 = $o->aenum2;
    $this->aenum22 = $o->aenum22;
    $this->manystring = $o->manystring;
    $this->manyint64 = $o->manyint64;
    $tmp = $o->aexample2;
    if ($tmp !== null) {
      $nv = new \foo\bar\example1_example2();
      $nv->CopyFrom($tmp);
      $this->aexample2 = $nv;
    }
    $tmp = $o->aexample22;
    if ($tmp !== null) {
      $nv = new \foo\bar\example2();
      $nv->CopyFrom($tmp);
      $this->aexample22 = $nv;
    }
    $tmp = $o->aexample23;
    if ($tmp !== null) {
      $nv = new \fiz\baz\example2();
      $nv->CopyFrom($tmp);
      $this->aexample23 = $nv;
    }
    $this->outoforder = $o->outoforder;
    $this->amap = $o->amap;
    foreach ($o->amap2 as $k => $v) {
      $nv = new \fiz\baz\example2();
      $nv->CopyFrom($v);
      $this->amap2[$k] = $nv;
    }
    $tmp = $o->anany;
    if ($tmp !== null) {
      $nv = new \google\protobuf\Any();
      $nv->CopyFrom($tmp);
      $this->anany = $nv;
    }
    $this->aoneof = $o->aoneof->Copy();
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class ExampleServiceClient {
  public function __construct(private \Grpc\Invoker $invoker) {
  }

  public async function OneToTwo(\Grpc\Context $ctx, \foo\bar\example1 $in, \Grpc\CallOption ...$co): Awaitable<\Errors\Result<\foo\bar\example2>> {
    $out = new \foo\bar\example2();
    $err = await $this->invoker->Invoke($ctx, '/foo.bar.ExampleService/OneToTwo', $in, $out, ...$co);
    if ($err->Ok()) {
      return \Errors\ResultV($out);
    }
    return \Errors\ResultE($err);
  }
}

interface ExampleServiceServer {
  public function OneToTwo(\Grpc\Context $ctx, \foo\bar\example1 $in): Awaitable<\Errors\Result<\foo\bar\example2>>;
}

function ExampleServiceServiceDescriptor(ExampleServiceServer $service): \Grpc\ServiceDesc {
  $methods = vec[];
  $handler = async (\Grpc\Context $ctx, \Grpc\Unmarshaller $u): Awaitable<\Errors\Result<\Protobuf\Message>> ==> {
    $in = new \foo\bar\example1();
    $err = $u->Unmarshal($in);
    if (!$err->Ok()) {
      return \Errors\ResultE(\Errors\Errorf('proto unmarshal: %s', $err->Error()));
    }
    return (await $service->OneToTwo($ctx, $in))->As<\Protobuf\Message>();
  };
  $methods []= new \Grpc\MethodDesc('OneToTwo', $handler);
  return new \Grpc\ServiceDesc('foo.bar.ExampleService', $methods);
}

function RegisterExampleServiceServer(\Grpc\Server $server, ExampleServiceServer $service): void {
  $server->RegisterService(ExampleServiceServiceDescriptor($service));
}

class XXX_FileDescriptor_test_example1__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'test/example1.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    return (string)\gzuncompress(\file_get_contents(\realpath(\dirname(__FILE__)) . '/example1_file_descriptor.pb.bin.gz'));
  }
}
