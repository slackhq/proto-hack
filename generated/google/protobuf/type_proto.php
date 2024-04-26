<?hh // strict
namespace google\protobuf;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: google/protobuf/type.proto

newtype Syntax_enum_t as int = int;
abstract class Syntax {
  const Syntax_enum_t SYNTAX_PROTO2 = 0;
  const Syntax_enum_t SYNTAX_PROTO3 = 1;
  private static dict<int, string> $itos = dict[
    0 => 'SYNTAX_PROTO2',
    1 => 'SYNTAX_PROTO3',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'SYNTAX_PROTO2' => 0,
    'SYNTAX_PROTO3' => 1,
  ];
  public static function FromMixed(mixed $m): Syntax_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): Syntax_enum_t {
    return $i;
  }
}

class Type implements \Protobuf\Message {
  public string $name;
  public vec<\google\protobuf\Field> $fields;
  public vec<string> $oneofs;
  public vec<\google\protobuf\Option> $options;
  public ?\google\protobuf\SourceContext $source_context;
  public \google\protobuf\Syntax_enum_t $syntax;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'name' => string,
    ?'fields' => vec<\google\protobuf\Field>,
    ?'oneofs' => vec<string>,
    ?'options' => vec<\google\protobuf\Option>,
    ?'source_context' => ?\google\protobuf\SourceContext,
    ?'syntax' => \google\protobuf\Syntax_enum_t,
  ) $s = shape()) {
    $this->name = $s['name'] ?? '';
    $this->fields = $s['fields'] ?? vec[];
    $this->oneofs = $s['oneofs'] ?? vec[];
    $this->options = $s['options'] ?? vec[];
    $this->source_context = $s['source_context'] ?? null;
    $this->syntax = $s['syntax'] ?? \google\protobuf\Syntax::FromInt(0);
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Type";
  }

  public static function ParseFrom(string $input): ?Type {
    $msg = new Type();
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
          $obj = new \google\protobuf\Field();
          $obj->MergeFrom($d->readDecoder());
          $this->fields []= $obj;
          break;
        case 3:
          $this->oneofs []= $d->readString();
          break;
        case 4:
          $obj = new \google\protobuf\Option();
          $obj->MergeFrom($d->readDecoder());
          $this->options []= $obj;
          break;
        case 5:
          if ($this->source_context is null) {
            $this->source_context = new \google\protobuf\SourceContext();
          }
          $this->source_context->MergeFrom($d->readDecoder());
          break;
        case 6:
          $this->syntax = \google\protobuf\Syntax::FromInt($d->readVarint());
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
    foreach ($this->fields as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 2);
    }
    foreach ($this->oneofs as $elem) {
      $e->writeTag(3, 2);
      $e->writeString($elem);
    }
    foreach ($this->options as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 4);
    }
    $msg = $this->source_context;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 5);
    }
    if ($this->syntax !== \google\protobuf\Syntax::FromInt(0)) {
      $e->writeTag(6, 0);
      $e->writeVarint($this->syntax);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('name', 'name', $this->name, false);
    $e->writeMessageList('fields', 'fields', $this->fields);
    $e->writePrimitiveList('oneofs', 'oneofs', $this->oneofs);
    $e->writeMessageList('options', 'options', $this->options);
    $e->writeMessage('source_context', 'sourceContext', $this->source_context, false);
    $e->writeEnum('syntax', 'syntax', \google\protobuf\Syntax::ToStringDict(), $this->syntax, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'fields':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\Field();
            $obj->MergeJsonFrom($vv);
            $this->fields []= $obj;
          }
          break;
        case 'oneofs':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $this->oneofs []= \Protobuf\Internal\JsonDecoder::readString($vv);
          }
          break;
        case 'options':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\Option();
            $obj->MergeJsonFrom($vv);
            $this->options []= $obj;
          }
          break;
        case 'source_context': case 'sourceContext':
          if ($v is null) break;
          if ($this->source_context is null) {
            $this->source_context = new \google\protobuf\SourceContext();
          }
          $this->source_context->MergeJsonFrom($v);
          break;
        case 'syntax':
          $this->syntax = \google\protobuf\Syntax::FromMixed($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is Type)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->name = $o->name;
    foreach ($o->fields as $v) {
      $nv = new \google\protobuf\Field();
      $nv->CopyFrom($v);
      $this->fields []= $nv;
    }
    $this->oneofs = $o->oneofs;
    foreach ($o->options as $v) {
      $nv = new \google\protobuf\Option();
      $nv->CopyFrom($v);
      $this->options []= $nv;
    }
    $tmp = $o->source_context;
    if ($tmp is nonnull) {
      $nv = new \google\protobuf\SourceContext();
      $nv->CopyFrom($tmp);
      $this->source_context = $nv;
    }
    $this->syntax = $o->syntax;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

newtype Field_Kind_enum_t as int = int;
abstract class Field_Kind {
  const Field_Kind_enum_t TYPE_UNKNOWN = 0;
  const Field_Kind_enum_t TYPE_DOUBLE = 1;
  const Field_Kind_enum_t TYPE_FLOAT = 2;
  const Field_Kind_enum_t TYPE_INT64 = 3;
  const Field_Kind_enum_t TYPE_UINT64 = 4;
  const Field_Kind_enum_t TYPE_INT32 = 5;
  const Field_Kind_enum_t TYPE_FIXED64 = 6;
  const Field_Kind_enum_t TYPE_FIXED32 = 7;
  const Field_Kind_enum_t TYPE_BOOL = 8;
  const Field_Kind_enum_t TYPE_STRING = 9;
  const Field_Kind_enum_t TYPE_GROUP = 10;
  const Field_Kind_enum_t TYPE_MESSAGE = 11;
  const Field_Kind_enum_t TYPE_BYTES = 12;
  const Field_Kind_enum_t TYPE_UINT32 = 13;
  const Field_Kind_enum_t TYPE_ENUM = 14;
  const Field_Kind_enum_t TYPE_SFIXED32 = 15;
  const Field_Kind_enum_t TYPE_SFIXED64 = 16;
  const Field_Kind_enum_t TYPE_SINT32 = 17;
  const Field_Kind_enum_t TYPE_SINT64 = 18;
  private static dict<int, string> $itos = dict[
    0 => 'TYPE_UNKNOWN',
    1 => 'TYPE_DOUBLE',
    2 => 'TYPE_FLOAT',
    3 => 'TYPE_INT64',
    4 => 'TYPE_UINT64',
    5 => 'TYPE_INT32',
    6 => 'TYPE_FIXED64',
    7 => 'TYPE_FIXED32',
    8 => 'TYPE_BOOL',
    9 => 'TYPE_STRING',
    10 => 'TYPE_GROUP',
    11 => 'TYPE_MESSAGE',
    12 => 'TYPE_BYTES',
    13 => 'TYPE_UINT32',
    14 => 'TYPE_ENUM',
    15 => 'TYPE_SFIXED32',
    16 => 'TYPE_SFIXED64',
    17 => 'TYPE_SINT32',
    18 => 'TYPE_SINT64',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'TYPE_UNKNOWN' => 0,
    'TYPE_DOUBLE' => 1,
    'TYPE_FLOAT' => 2,
    'TYPE_INT64' => 3,
    'TYPE_UINT64' => 4,
    'TYPE_INT32' => 5,
    'TYPE_FIXED64' => 6,
    'TYPE_FIXED32' => 7,
    'TYPE_BOOL' => 8,
    'TYPE_STRING' => 9,
    'TYPE_GROUP' => 10,
    'TYPE_MESSAGE' => 11,
    'TYPE_BYTES' => 12,
    'TYPE_UINT32' => 13,
    'TYPE_ENUM' => 14,
    'TYPE_SFIXED32' => 15,
    'TYPE_SFIXED64' => 16,
    'TYPE_SINT32' => 17,
    'TYPE_SINT64' => 18,
  ];
  public static function FromMixed(mixed $m): Field_Kind_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): Field_Kind_enum_t {
    return $i;
  }
}

newtype Field_Cardinality_enum_t as int = int;
abstract class Field_Cardinality {
  const Field_Cardinality_enum_t CARDINALITY_UNKNOWN = 0;
  const Field_Cardinality_enum_t CARDINALITY_OPTIONAL = 1;
  const Field_Cardinality_enum_t CARDINALITY_REQUIRED = 2;
  const Field_Cardinality_enum_t CARDINALITY_REPEATED = 3;
  private static dict<int, string> $itos = dict[
    0 => 'CARDINALITY_UNKNOWN',
    1 => 'CARDINALITY_OPTIONAL',
    2 => 'CARDINALITY_REQUIRED',
    3 => 'CARDINALITY_REPEATED',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'CARDINALITY_UNKNOWN' => 0,
    'CARDINALITY_OPTIONAL' => 1,
    'CARDINALITY_REQUIRED' => 2,
    'CARDINALITY_REPEATED' => 3,
  ];
  public static function FromMixed(mixed $m): Field_Cardinality_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): Field_Cardinality_enum_t {
    return $i;
  }
}

class Field implements \Protobuf\Message {
  public \google\protobuf\Field_Kind_enum_t $kind;
  public \google\protobuf\Field_Cardinality_enum_t $cardinality;
  public int $number;
  public string $name;
  public string $type_url;
  public int $oneof_index;
  public bool $packed;
  public vec<\google\protobuf\Option> $options;
  public string $json_name;
  public string $default_value;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'kind' => \google\protobuf\Field_Kind_enum_t,
    ?'cardinality' => \google\protobuf\Field_Cardinality_enum_t,
    ?'number' => int,
    ?'name' => string,
    ?'type_url' => string,
    ?'oneof_index' => int,
    ?'packed' => bool,
    ?'options' => vec<\google\protobuf\Option>,
    ?'json_name' => string,
    ?'default_value' => string,
  ) $s = shape()) {
    $this->kind = $s['kind'] ?? \google\protobuf\Field_Kind::FromInt(0);
    $this->cardinality = $s['cardinality'] ?? \google\protobuf\Field_Cardinality::FromInt(0);
    $this->number = $s['number'] ?? 0;
    $this->name = $s['name'] ?? '';
    $this->type_url = $s['type_url'] ?? '';
    $this->oneof_index = $s['oneof_index'] ?? 0;
    $this->packed = $s['packed'] ?? false;
    $this->options = $s['options'] ?? vec[];
    $this->json_name = $s['json_name'] ?? '';
    $this->default_value = $s['default_value'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Field";
  }

  public static function ParseFrom(string $input): ?Field {
    $msg = new Field();
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
          $this->kind = \google\protobuf\Field_Kind::FromInt($d->readVarint());
          break;
        case 2:
          $this->cardinality = \google\protobuf\Field_Cardinality::FromInt($d->readVarint());
          break;
        case 3:
          $this->number = $d->readVarint32Signed();
          break;
        case 4:
          $this->name = $d->readString();
          break;
        case 6:
          $this->type_url = $d->readString();
          break;
        case 7:
          $this->oneof_index = $d->readVarint32Signed();
          break;
        case 8:
          $this->packed = $d->readBool();
          break;
        case 9:
          $obj = new \google\protobuf\Option();
          $obj->MergeFrom($d->readDecoder());
          $this->options []= $obj;
          break;
        case 10:
          $this->json_name = $d->readString();
          break;
        case 11:
          $this->default_value = $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->kind !== \google\protobuf\Field_Kind::FromInt(0)) {
      $e->writeTag(1, 0);
      $e->writeVarint($this->kind);
    }
    if ($this->cardinality !== \google\protobuf\Field_Cardinality::FromInt(0)) {
      $e->writeTag(2, 0);
      $e->writeVarint($this->cardinality);
    }
    if ($this->number !== 0) {
      $e->writeTag(3, 0);
      $e->writeVarint($this->number);
    }
    if ($this->name !== '') {
      $e->writeTag(4, 2);
      $e->writeString($this->name);
    }
    if ($this->type_url !== '') {
      $e->writeTag(6, 2);
      $e->writeString($this->type_url);
    }
    if ($this->oneof_index !== 0) {
      $e->writeTag(7, 0);
      $e->writeVarint($this->oneof_index);
    }
    if ($this->packed !== false) {
      $e->writeTag(8, 0);
      $e->writeBool($this->packed);
    }
    foreach ($this->options as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 9);
    }
    if ($this->json_name !== '') {
      $e->writeTag(10, 2);
      $e->writeString($this->json_name);
    }
    if ($this->default_value !== '') {
      $e->writeTag(11, 2);
      $e->writeString($this->default_value);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeEnum('kind', 'kind', \google\protobuf\Field_Kind::ToStringDict(), $this->kind, false);
    $e->writeEnum('cardinality', 'cardinality', \google\protobuf\Field_Cardinality::ToStringDict(), $this->cardinality, false);
    $e->writeInt32('number', 'number', $this->number, false);
    $e->writeString('name', 'name', $this->name, false);
    $e->writeString('type_url', 'typeUrl', $this->type_url, false);
    $e->writeInt32('oneof_index', 'oneofIndex', $this->oneof_index, false);
    $e->writeBool('packed', 'packed', $this->packed, false);
    $e->writeMessageList('options', 'options', $this->options);
    $e->writeString('json_name', 'jsonName', $this->json_name, false);
    $e->writeString('default_value', 'defaultValue', $this->default_value, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'kind':
          $this->kind = \google\protobuf\Field_Kind::FromMixed($v);
          break;
        case 'cardinality':
          $this->cardinality = \google\protobuf\Field_Cardinality::FromMixed($v);
          break;
        case 'number':
          $this->number = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'type_url': case 'typeUrl':
          $this->type_url = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'oneof_index': case 'oneofIndex':
          $this->oneof_index = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'packed':
          $this->packed = \Protobuf\Internal\JsonDecoder::readBool($v);
          break;
        case 'options':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\Option();
            $obj->MergeJsonFrom($vv);
            $this->options []= $obj;
          }
          break;
        case 'json_name': case 'jsonName':
          $this->json_name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'default_value': case 'defaultValue':
          $this->default_value = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is Field)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->kind = $o->kind;
    $this->cardinality = $o->cardinality;
    $this->number = $o->number;
    $this->name = $o->name;
    $this->type_url = $o->type_url;
    $this->oneof_index = $o->oneof_index;
    $this->packed = $o->packed;
    foreach ($o->options as $v) {
      $nv = new \google\protobuf\Option();
      $nv->CopyFrom($v);
      $this->options []= $nv;
    }
    $this->json_name = $o->json_name;
    $this->default_value = $o->default_value;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class pb_Enum implements \Protobuf\Message {
  public string $name;
  public vec<\google\protobuf\EnumValue> $enumvalue;
  public vec<\google\protobuf\Option> $options;
  public ?\google\protobuf\SourceContext $source_context;
  public \google\protobuf\Syntax_enum_t $syntax;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'name' => string,
    ?'enumvalue' => vec<\google\protobuf\EnumValue>,
    ?'options' => vec<\google\protobuf\Option>,
    ?'source_context' => ?\google\protobuf\SourceContext,
    ?'syntax' => \google\protobuf\Syntax_enum_t,
  ) $s = shape()) {
    $this->name = $s['name'] ?? '';
    $this->enumvalue = $s['enumvalue'] ?? vec[];
    $this->options = $s['options'] ?? vec[];
    $this->source_context = $s['source_context'] ?? null;
    $this->syntax = $s['syntax'] ?? \google\protobuf\Syntax::FromInt(0);
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Enum";
  }

  public static function ParseFrom(string $input): ?pb_Enum {
    $msg = new pb_Enum();
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
          $obj = new \google\protobuf\EnumValue();
          $obj->MergeFrom($d->readDecoder());
          $this->enumvalue []= $obj;
          break;
        case 3:
          $obj = new \google\protobuf\Option();
          $obj->MergeFrom($d->readDecoder());
          $this->options []= $obj;
          break;
        case 4:
          if ($this->source_context is null) {
            $this->source_context = new \google\protobuf\SourceContext();
          }
          $this->source_context->MergeFrom($d->readDecoder());
          break;
        case 5:
          $this->syntax = \google\protobuf\Syntax::FromInt($d->readVarint());
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
    foreach ($this->enumvalue as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 2);
    }
    foreach ($this->options as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 3);
    }
    $msg = $this->source_context;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 4);
    }
    if ($this->syntax !== \google\protobuf\Syntax::FromInt(0)) {
      $e->writeTag(5, 0);
      $e->writeVarint($this->syntax);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('name', 'name', $this->name, false);
    $e->writeMessageList('enumvalue', 'enumvalue', $this->enumvalue);
    $e->writeMessageList('options', 'options', $this->options);
    $e->writeMessage('source_context', 'sourceContext', $this->source_context, false);
    $e->writeEnum('syntax', 'syntax', \google\protobuf\Syntax::ToStringDict(), $this->syntax, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'enumvalue':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\EnumValue();
            $obj->MergeJsonFrom($vv);
            $this->enumvalue []= $obj;
          }
          break;
        case 'options':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\Option();
            $obj->MergeJsonFrom($vv);
            $this->options []= $obj;
          }
          break;
        case 'source_context': case 'sourceContext':
          if ($v is null) break;
          if ($this->source_context is null) {
            $this->source_context = new \google\protobuf\SourceContext();
          }
          $this->source_context->MergeJsonFrom($v);
          break;
        case 'syntax':
          $this->syntax = \google\protobuf\Syntax::FromMixed($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is pb_Enum)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->name = $o->name;
    foreach ($o->enumvalue as $v) {
      $nv = new \google\protobuf\EnumValue();
      $nv->CopyFrom($v);
      $this->enumvalue []= $nv;
    }
    foreach ($o->options as $v) {
      $nv = new \google\protobuf\Option();
      $nv->CopyFrom($v);
      $this->options []= $nv;
    }
    $tmp = $o->source_context;
    if ($tmp is nonnull) {
      $nv = new \google\protobuf\SourceContext();
      $nv->CopyFrom($tmp);
      $this->source_context = $nv;
    }
    $this->syntax = $o->syntax;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class EnumValue implements \Protobuf\Message {
  public string $name;
  public int $number;
  public vec<\google\protobuf\Option> $options;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'name' => string,
    ?'number' => int,
    ?'options' => vec<\google\protobuf\Option>,
  ) $s = shape()) {
    $this->name = $s['name'] ?? '';
    $this->number = $s['number'] ?? 0;
    $this->options = $s['options'] ?? vec[];
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.EnumValue";
  }

  public static function ParseFrom(string $input): ?EnumValue {
    $msg = new EnumValue();
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
          $this->number = $d->readVarint32Signed();
          break;
        case 3:
          $obj = new \google\protobuf\Option();
          $obj->MergeFrom($d->readDecoder());
          $this->options []= $obj;
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
    if ($this->number !== 0) {
      $e->writeTag(2, 0);
      $e->writeVarint($this->number);
    }
    foreach ($this->options as $msg) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 3);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('name', 'name', $this->name, false);
    $e->writeInt32('number', 'number', $this->number, false);
    $e->writeMessageList('options', 'options', $this->options);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'number':
          $this->number = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
          break;
        case 'options':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $obj = new \google\protobuf\Option();
            $obj->MergeJsonFrom($vv);
            $this->options []= $obj;
          }
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is EnumValue)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->name = $o->name;
    $this->number = $o->number;
    foreach ($o->options as $v) {
      $nv = new \google\protobuf\Option();
      $nv->CopyFrom($v);
      $this->options []= $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class Option implements \Protobuf\Message {
  public string $name;
  public ?\google\protobuf\Any $value;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'name' => string,
    ?'value' => ?\google\protobuf\Any,
  ) $s = shape()) {
    $this->name = $s['name'] ?? '';
    $this->value = $s['value'] ?? null;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "google.protobuf.Option";
  }

  public static function ParseFrom(string $input): ?Option {
    $msg = new Option();
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
          if ($this->value is null) {
            $this->value = new \google\protobuf\Any();
          }
          $this->value->MergeFrom($d->readDecoder());
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
    $msg = $this->value;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 2);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('name', 'name', $this->name, false);
    $e->writeMessage('value', 'value', $this->value, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'value':
          if ($v is null) break;
          if ($this->value is null) {
            $this->value = new \google\protobuf\Any();
          }
          $this->value->MergeJsonFrom($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is Option)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->name = $o->name;
    $tmp = $o->value;
    if ($tmp is nonnull) {
      $nv = new \google\protobuf\Any();
      $nv->CopyFrom($tmp);
      $this->value = $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_google_protobuf_type__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'google/protobuf/type.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 3135 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\xa4\x55\xdd\x8e\xda\x46\x14\x8e\x8d\xf1\xe2\xc3\xc2\x4e\x26\x51\xe2\x6c\xa4\x14\xd1\x5e\xa0\x48\x35\x2a\xac\x56\xbd\x35\x8b\x97\x5a\x4b\x6c\x77\x30\x4d\xb6\x37\xc8\xb\x5e\x44\xd6\x8c\x11\xb6\xdb\x45\x7d\x86\xbe\x44\x2f\x7b\xdd\x87\xe8\x23\xf5\xae\xd5\x8c\xc1\x98\x9f\x4a\x69\x73\xe7\xf3\x9d\xef\x7c\xe7\x67\x8e\x67\xe0\x7c\x1a\x86\xd3\xc0\x6f\x2e\x96\x61\x1c\xde\x25\xf7\xcd\x78\xb5\xf0\x35\x6e\xe1\xb3\xd4\xa7\x6d\x7c\xe7\xaf\xf6\xc9\x1e\x5d\xa5\xde\xf3\xaf\xf6\x5d\x51\x98\x2c\xc7\xfe\x68\x1c\xd2\xd8\x7f\x8c\x53\x56\xfd\x57\x11\x24\x77\xb5\xf0\x31\x6\x89\x7a\x73\x5f\x15\x6a\x42\x43\x21\xfc\x1b\x6b\x20\xdf\xcf\xfc\x60\x12\xa9\x62\xad\xd0\x28\xb7\x5e\x68\x7b\xf9\xb5\x6b\xe6\x26\x6b\x16\x7e\x1\x72\x48\xfd\xf0\x3e\x52\xb\xb5\x42\x43\x21\x6b\xb\x7f\x3\x27\xe1\x22\x9e\x85\x34\x52\x25\x2e\xf4\xf2\x40\xc8\xe6\x7e\xb2\xe1\x61\x3\xaa\xbb\xf5\xaa\xc5\x9a\xd0\x28\xb7\xde\x1c\x44\xe\x38\xed\x2a\x65\x91\x4a\x94\x37\x71\x13\xe4\x68\x45\x63\xef\x51\x95\x6b\x42\xa3\x7a\x24\xf1\x80\xbb\xc9\x9a\x56\xff\x43\x86\x22\x6f\xa\x37\x41\x7a\x98\xd1\x9\x1f\x48\xb5\xf5\xfa\x78\xeb\xda\xcd\x8c\x4e\x8\x27\xe2\x2e\x94\xc7\xde\x72\x32\xa3\x5e\x30\x8b\x57\xaa\xc8\xe3\xea\xff\x12\x77\xb5\x65\x92\x7c\x18\x9b\x21\x4d\xe6\x77\xfe\x52\x2d\xd4\x84\x46\x91\xac\xad\xec\x7c\xa4\xdc\xf9\xbc\x82\x12\x5b\x8e\x51\xb2\xc\x78\x7f\xa\x39\x61\xf6\x70\x19\xe0\x2f\xa0\xcc\x87\x3f\x9a\xd1\x89\xff\xa8\x9e\x70\x2d\xe0\x90\xc9\x10\x96\x67\xe1\x8d\x1f\xfc\x89\x5a\xaa\x9\x8d\x12\x59\x5b\xf9\xb3\x52\x3e\xf1\xac\x5e\x83\xf2\x31\xa\xe9\x88\xd7\x7\xbc\x8e\x12\x3\x2c\x56\xe3\x97\x50\x99\xf8\xf7\x5e\x12\xc4\xa3\x9f\xbc\x20\xf1\xd5\x32\x27\x9c\xae\xc1\x1f\x18\x56\xff\x53\x4\x89\x4d\x12\x23\x38\x75\x6f\x1d\x63\x34\xb4\x6e\x2c\xfb\xbd\x85\x9e\xe0\x33\x28\x73\xa4\x6b\xf\x3b\x7d\x3\x9\xb8\xa\xc0\x81\xeb\xbe\xad\xbb\x48\xcc\x6c\xd3\x72\x2f\x2f\x50\x21\xb\x18\xa6\x80\x94\x27\xb4\x5b\xa8\x98\xe5\xb8\x36\x3f\x18\xdd\xcb\xb\x24\xef\x22\xed\x16\x3a\xc1\x15\x50\x38\xd2\xb1\xed\x3e\x2a\x65\x9a\x3\x97\x98\x56\xf\x29\x99\x66\x8f\xd8\x43\x7\x41\xa6\xf0\xce\x18\xc\xf4\x9e\x81\xca\x19\xa3\x73\xeb\x1a\x3\x74\xba\x53\x56\xbb\x85\x2a\x59\xa\xc3\x1a\xbe\x43\x55\xfc\x14\x2a\x69\x8a\x4d\x11\x67\x7b\xd0\xe5\x5\x42\xdb\x42\x52\x95\xa7\x3b\xc0\xe5\x5\xc2\xf5\x18\xca\xb9\xdd\xc2\x2f\xe1\xd9\x95\x4e\xba\xa6\xa5\xf7\x4d\xf7\x36\x37\x57\x15\x9e\xe7\x1d\xb6\xe3\x9a\xb6\xa5\xf7\x91\xb0\xef\x21\xc6\xf7\x43\x93\x18\x5d\x24\x1e\x7a\x1c\x43\x77\x8d\x2e\x2a\xd4\xff\x16\x40\x32\x68\x32\x3f\x7a\x8d\x7c\xb\x8a\x4f\x93\x79\x7a\xfc\xe9\x4d\x72\x7e\xb0\x54\x2c\x9a\x2f\x3\xd9\x92\xf3\xcb\x58\xf8\xdf\x17\x87\xf4\x79\x17\x47\xf1\xd3\x2e\x8e\x8f\xa0\x64\x2d\x1c\x9d\xc2\xf6\xc7\x16\x77\x7e\xec\xff\xde\x63\xfd\x3b\x90\x53\xe8\x68\xa2\xb7\x50\xdc\x8c\x9a\x35\xfe\xfc\x40\x4e\xa7\x2b\x92\x52\xde\x6a\x20\xa7\x7d\xb0\x65\x1b\xdc\x5a\xae\xfe\x61\xe4\x10\xdb\xb5\x5b\xe8\xc9\x3e\xd4\x46\x42\xe7\x17\x78\x36\xe\xe7\xfb\x8a\x1d\x85\x3d\x21\xe\xb3\x1c\xe1\xc7\xaf\xd7\xde\x69\x18\x78\x74\xaa\x85\xcb\xe9\xee\x5b\x16\x35\x1f\x68\xf8\x33\xe5\xdf\x8b\xbb\xbf\x4\xe1\x37\xb1\xd0\x73\x3a\xbf\x8b\x6f\x7a\x69\xa0\xb3\x29\xf4\xbd\x1f\x4\x37\x8c\xcb\xe4\xa3\x3b\x99\xcb\xb4\xff\x9\x0\x0\xff\xff\xb4\x67\x14\x7d");
  }
}
