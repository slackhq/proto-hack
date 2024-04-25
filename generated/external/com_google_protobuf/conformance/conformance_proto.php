<?hh // strict
namespace conformance;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: external/com_google_protobuf/conformance/conformance.proto

newtype WireFormat_enum_t as int = int;
abstract class WireFormat {
  const WireFormat_enum_t UNSPECIFIED = 0;
  const WireFormat_enum_t PROTOBUF = 1;
  const WireFormat_enum_t JSON = 2;
  const WireFormat_enum_t JSPB = 3;
  const WireFormat_enum_t TEXT_FORMAT = 4;
  private static dict<int, string> $itos = dict[
    0 => 'UNSPECIFIED',
    1 => 'PROTOBUF',
    2 => 'JSON',
    3 => 'JSPB',
    4 => 'TEXT_FORMAT',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'UNSPECIFIED' => 0,
    'PROTOBUF' => 1,
    'JSON' => 2,
    'JSPB' => 3,
    'TEXT_FORMAT' => 4,
  ];
  public static function FromMixed(mixed $m): WireFormat_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): WireFormat_enum_t {
    return $i;
  }
}

newtype TestCategory_enum_t as int = int;
abstract class TestCategory {
  const TestCategory_enum_t UNSPECIFIED_TEST = 0;
  const TestCategory_enum_t BINARY_TEST = 1;
  const TestCategory_enum_t JSON_TEST = 2;
  const TestCategory_enum_t JSON_IGNORE_UNKNOWN_PARSING_TEST = 3;
  const TestCategory_enum_t JSPB_TEST = 4;
  const TestCategory_enum_t TEXT_FORMAT_TEST = 5;
  private static dict<int, string> $itos = dict[
    0 => 'UNSPECIFIED_TEST',
    1 => 'BINARY_TEST',
    2 => 'JSON_TEST',
    3 => 'JSON_IGNORE_UNKNOWN_PARSING_TEST',
    4 => 'JSPB_TEST',
    5 => 'TEXT_FORMAT_TEST',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'UNSPECIFIED_TEST' => 0,
    'BINARY_TEST' => 1,
    'JSON_TEST' => 2,
    'JSON_IGNORE_UNKNOWN_PARSING_TEST' => 3,
    'JSPB_TEST' => 4,
    'TEXT_FORMAT_TEST' => 5,
  ];
  public static function FromMixed(mixed $m): TestCategory_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): TestCategory_enum_t {
    return $i;
  }
}

class FailureSet implements \Protobuf\Message {
  public vec<string> $failure;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'failure' => vec<string>,
  ) $s = shape()) {
    $this->failure = $s['failure'] ?? vec[];
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "conformance.FailureSet";
  }

  public static function ParseFrom(string $input): ?FailureSet {
    $msg = new FailureSet();
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
          $this->failure []= $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    foreach ($this->failure as $elem) {
      $e->writeTag(1, 2);
      $e->writeString($elem);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writePrimitiveList('failure', 'failure', $this->failure);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'failure':
          foreach(\Protobuf\Internal\JsonDecoder::readList($v) as $vv) {
            $this->failure []= \Protobuf\Internal\JsonDecoder::readString($vv);
          }
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is FailureSet)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->failure = $o->failure;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

enum ConformanceRequest_payload_oneof_t: int {
  NOT_SET = 0;
  protobuf_payload = 1;
  json_payload = 2;
  jspb_payload = 7;
  text_payload = 8;
}

interface ConformanceRequest_payload {
  public function WhichOneof(): ConformanceRequest_payload_oneof_t;
  public function WriteTo(\Protobuf\Internal\Encoder $e): void;
  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void;
  public function Copy(): ConformanceRequest_payload;
}

class ConformanceRequest_payload_NOT_SET implements ConformanceRequest_payload {
  public function WhichOneof(): ConformanceRequest_payload_oneof_t {
    return ConformanceRequest_payload_oneof_t::NOT_SET;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {}

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {}

  public function Copy(): ConformanceRequest_payload { return $this; }
}

class ConformanceRequest_payload_protobuf_payload implements ConformanceRequest_payload {
  public function __construct(public string $protobuf_payload) {}

  public function WhichOneof(): ConformanceRequest_payload_oneof_t {
    return ConformanceRequest_payload_oneof_t::protobuf_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(1, 2);;
    $e->writeString($this->protobuf_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeBytes('protobuf_payload', 'protobufPayload', $this->protobuf_payload, true);
  }

  public function Copy(): ConformanceRequest_payload {
    return new ConformanceRequest_payload_protobuf_payload($this->protobuf_payload);
  }
}

class ConformanceRequest_payload_json_payload implements ConformanceRequest_payload {
  public function __construct(public string $json_payload) {}

  public function WhichOneof(): ConformanceRequest_payload_oneof_t {
    return ConformanceRequest_payload_oneof_t::json_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(2, 2);;
    $e->writeString($this->json_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('json_payload', 'jsonPayload', $this->json_payload, true);
  }

  public function Copy(): ConformanceRequest_payload {
    return new ConformanceRequest_payload_json_payload($this->json_payload);
  }
}

class ConformanceRequest_payload_jspb_payload implements ConformanceRequest_payload {
  public function __construct(public string $jspb_payload) {}

  public function WhichOneof(): ConformanceRequest_payload_oneof_t {
    return ConformanceRequest_payload_oneof_t::jspb_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(7, 2);;
    $e->writeString($this->jspb_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('jspb_payload', 'jspbPayload', $this->jspb_payload, true);
  }

  public function Copy(): ConformanceRequest_payload {
    return new ConformanceRequest_payload_jspb_payload($this->jspb_payload);
  }
}

class ConformanceRequest_payload_text_payload implements ConformanceRequest_payload {
  public function __construct(public string $text_payload) {}

  public function WhichOneof(): ConformanceRequest_payload_oneof_t {
    return ConformanceRequest_payload_oneof_t::text_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(8, 2);;
    $e->writeString($this->text_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('text_payload', 'textPayload', $this->text_payload, true);
  }

  public function Copy(): ConformanceRequest_payload {
    return new ConformanceRequest_payload_text_payload($this->text_payload);
  }
}

class ConformanceRequest implements \Protobuf\Message {
  public \conformance\WireFormat_enum_t $requested_output_format;
  public string $message_type;
  public \conformance\TestCategory_enum_t $test_category;
  public ?\conformance\JspbEncodingConfig $jspb_encoding_options;
  public bool $print_unknown_fields;
  public ConformanceRequest_payload $payload;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'requested_output_format' => \conformance\WireFormat_enum_t,
    ?'message_type' => string,
    ?'test_category' => \conformance\TestCategory_enum_t,
    ?'jspb_encoding_options' => ?\conformance\JspbEncodingConfig,
    ?'print_unknown_fields' => bool,
    ?'payload' => ConformanceRequest_payload,
  ) $s = shape()) {
    $this->requested_output_format = $s['requested_output_format'] ?? \conformance\WireFormat::FromInt(0);
    $this->message_type = $s['message_type'] ?? '';
    $this->test_category = $s['test_category'] ?? \conformance\TestCategory::FromInt(0);
    $this->jspb_encoding_options = $s['jspb_encoding_options'] ?? null;
    $this->print_unknown_fields = $s['print_unknown_fields'] ?? false;
    $this->payload = $s['payload'] ?? new ConformanceRequest_payload_NOT_SET();
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "conformance.ConformanceRequest";
  }

  public static function ParseFrom(string $input): ?ConformanceRequest {
    $msg = new ConformanceRequest();
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
          $this->payload = new ConformanceRequest_payload_protobuf_payload($d->readString());
          break;
        case 2:
          $this->payload = new ConformanceRequest_payload_json_payload($d->readString());
          break;
        case 3:
          $this->requested_output_format = \conformance\WireFormat::FromInt($d->readVarint());
          break;
        case 4:
          $this->message_type = $d->readString();
          break;
        case 5:
          $this->test_category = \conformance\TestCategory::FromInt($d->readVarint());
          break;
        case 6:
          if ($this->jspb_encoding_options is null) {
            $this->jspb_encoding_options = new \conformance\JspbEncodingConfig();
          }
          $this->jspb_encoding_options->MergeFrom($d->readDecoder());
          break;
        case 7:
          $this->payload = new ConformanceRequest_payload_jspb_payload($d->readString());
          break;
        case 8:
          $this->payload = new ConformanceRequest_payload_text_payload($d->readString());
          break;
        case 9:
          $this->print_unknown_fields = $d->readBool();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->requested_output_format !== \conformance\WireFormat::FromInt(0)) {
      $e->writeTag(3, 0);
      $e->writeVarint($this->requested_output_format);
    }
    if ($this->message_type !== '') {
      $e->writeTag(4, 2);
      $e->writeString($this->message_type);
    }
    if ($this->test_category !== \conformance\TestCategory::FromInt(0)) {
      $e->writeTag(5, 0);
      $e->writeVarint($this->test_category);
    }
    $msg = $this->jspb_encoding_options;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 6);
    }
    if ($this->print_unknown_fields !== false) {
      $e->writeTag(9, 0);
      $e->writeBool($this->print_unknown_fields);
    }
    $this->payload->WriteTo($e);
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeEnum('requested_output_format', 'requestedOutputFormat', \conformance\WireFormat::ToStringDict(), $this->requested_output_format, false);
    $e->writeString('message_type', 'messageType', $this->message_type, false);
    $e->writeEnum('test_category', 'testCategory', \conformance\TestCategory::ToStringDict(), $this->test_category, false);
    $e->writeMessage('jspb_encoding_options', 'jspbEncodingOptions', $this->jspb_encoding_options, false);
    $e->writeBool('print_unknown_fields', 'printUnknownFields', $this->print_unknown_fields, false);
    $this->payload->WriteJsonTo($e);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'protobuf_payload': case 'protobufPayload':
          $this->payload = new ConformanceRequest_payload_protobuf_payload(\Protobuf\Internal\JsonDecoder::readBytes($v));
          break;
        case 'json_payload': case 'jsonPayload':
          $this->payload = new ConformanceRequest_payload_json_payload(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'requested_output_format': case 'requestedOutputFormat':
          $this->requested_output_format = \conformance\WireFormat::FromMixed($v);
          break;
        case 'message_type': case 'messageType':
          $this->message_type = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        case 'test_category': case 'testCategory':
          $this->test_category = \conformance\TestCategory::FromMixed($v);
          break;
        case 'jspb_encoding_options': case 'jspbEncodingOptions':
          if ($v is null) break;
          if ($this->jspb_encoding_options is null) {
            $this->jspb_encoding_options = new \conformance\JspbEncodingConfig();
          }
          $this->jspb_encoding_options->MergeJsonFrom($v);
          break;
        case 'jspb_payload': case 'jspbPayload':
          $this->payload = new ConformanceRequest_payload_jspb_payload(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'text_payload': case 'textPayload':
          $this->payload = new ConformanceRequest_payload_text_payload(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'print_unknown_fields': case 'printUnknownFields':
          $this->print_unknown_fields = \Protobuf\Internal\JsonDecoder::readBool($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is ConformanceRequest)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->requested_output_format = $o->requested_output_format;
    $this->message_type = $o->message_type;
    $this->test_category = $o->test_category;
    $tmp = $o->jspb_encoding_options;
    if ($tmp is nonnull) {
      $nv = new \conformance\JspbEncodingConfig();
      $nv->CopyFrom($tmp);
      $this->jspb_encoding_options = $nv;
    }
    $this->print_unknown_fields = $o->print_unknown_fields;
    $this->payload = $o->payload->Copy();
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

enum ConformanceResponse_result_oneof_t: int {
  NOT_SET = 0;
  parse_error = 1;
  serialize_error = 6;
  runtime_error = 2;
  protobuf_payload = 3;
  json_payload = 4;
  skipped = 5;
  jspb_payload = 7;
  text_payload = 8;
}

interface ConformanceResponse_result {
  public function WhichOneof(): ConformanceResponse_result_oneof_t;
  public function WriteTo(\Protobuf\Internal\Encoder $e): void;
  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void;
  public function Copy(): ConformanceResponse_result;
}

class ConformanceResponse_result_NOT_SET implements ConformanceResponse_result {
  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::NOT_SET;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {}

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {}

  public function Copy(): ConformanceResponse_result { return $this; }
}

class ConformanceResponse_result_parse_error implements ConformanceResponse_result {
  public function __construct(public string $parse_error) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::parse_error;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(1, 2);;
    $e->writeString($this->parse_error);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('parse_error', 'parseError', $this->parse_error, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_parse_error($this->parse_error);
  }
}

class ConformanceResponse_result_serialize_error implements ConformanceResponse_result {
  public function __construct(public string $serialize_error) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::serialize_error;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(6, 2);;
    $e->writeString($this->serialize_error);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('serialize_error', 'serializeError', $this->serialize_error, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_serialize_error($this->serialize_error);
  }
}

class ConformanceResponse_result_runtime_error implements ConformanceResponse_result {
  public function __construct(public string $runtime_error) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::runtime_error;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(2, 2);;
    $e->writeString($this->runtime_error);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('runtime_error', 'runtimeError', $this->runtime_error, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_runtime_error($this->runtime_error);
  }
}

class ConformanceResponse_result_protobuf_payload implements ConformanceResponse_result {
  public function __construct(public string $protobuf_payload) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::protobuf_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(3, 2);;
    $e->writeString($this->protobuf_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeBytes('protobuf_payload', 'protobufPayload', $this->protobuf_payload, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_protobuf_payload($this->protobuf_payload);
  }
}

class ConformanceResponse_result_json_payload implements ConformanceResponse_result {
  public function __construct(public string $json_payload) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::json_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(4, 2);;
    $e->writeString($this->json_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('json_payload', 'jsonPayload', $this->json_payload, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_json_payload($this->json_payload);
  }
}

class ConformanceResponse_result_skipped implements ConformanceResponse_result {
  public function __construct(public string $skipped) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::skipped;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(5, 2);;
    $e->writeString($this->skipped);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('skipped', 'skipped', $this->skipped, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_skipped($this->skipped);
  }
}

class ConformanceResponse_result_jspb_payload implements ConformanceResponse_result {
  public function __construct(public string $jspb_payload) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::jspb_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(7, 2);;
    $e->writeString($this->jspb_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('jspb_payload', 'jspbPayload', $this->jspb_payload, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_jspb_payload($this->jspb_payload);
  }
}

class ConformanceResponse_result_text_payload implements ConformanceResponse_result {
  public function __construct(public string $text_payload) {}

  public function WhichOneof(): ConformanceResponse_result_oneof_t {
    return ConformanceResponse_result_oneof_t::text_payload;
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $e->writeTag(8, 2);;
    $e->writeString($this->text_payload);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('text_payload', 'textPayload', $this->text_payload, true);
  }

  public function Copy(): ConformanceResponse_result {
    return new ConformanceResponse_result_text_payload($this->text_payload);
  }
}

class ConformanceResponse implements \Protobuf\Message {
  public ConformanceResponse_result $result;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'result' => ConformanceResponse_result,
  ) $s = shape()) {
    $this->result = $s['result'] ?? new ConformanceResponse_result_NOT_SET();
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "conformance.ConformanceResponse";
  }

  public static function ParseFrom(string $input): ?ConformanceResponse {
    $msg = new ConformanceResponse();
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
          $this->result = new ConformanceResponse_result_parse_error($d->readString());
          break;
        case 2:
          $this->result = new ConformanceResponse_result_runtime_error($d->readString());
          break;
        case 3:
          $this->result = new ConformanceResponse_result_protobuf_payload($d->readString());
          break;
        case 4:
          $this->result = new ConformanceResponse_result_json_payload($d->readString());
          break;
        case 5:
          $this->result = new ConformanceResponse_result_skipped($d->readString());
          break;
        case 6:
          $this->result = new ConformanceResponse_result_serialize_error($d->readString());
          break;
        case 7:
          $this->result = new ConformanceResponse_result_jspb_payload($d->readString());
          break;
        case 8:
          $this->result = new ConformanceResponse_result_text_payload($d->readString());
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $this->result->WriteTo($e);
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $this->result->WriteJsonTo($e);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'parse_error': case 'parseError':
          $this->result = new ConformanceResponse_result_parse_error(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'runtime_error': case 'runtimeError':
          $this->result = new ConformanceResponse_result_runtime_error(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'protobuf_payload': case 'protobufPayload':
          $this->result = new ConformanceResponse_result_protobuf_payload(\Protobuf\Internal\JsonDecoder::readBytes($v));
          break;
        case 'json_payload': case 'jsonPayload':
          $this->result = new ConformanceResponse_result_json_payload(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'skipped':
          $this->result = new ConformanceResponse_result_skipped(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'serialize_error': case 'serializeError':
          $this->result = new ConformanceResponse_result_serialize_error(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'jspb_payload': case 'jspbPayload':
          $this->result = new ConformanceResponse_result_jspb_payload(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        case 'text_payload': case 'textPayload':
          $this->result = new ConformanceResponse_result_text_payload(\Protobuf\Internal\JsonDecoder::readString($v));
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is ConformanceResponse)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->result = $o->result->Copy();
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class JspbEncodingConfig implements \Protobuf\Message {
  public bool $use_jspb_array_any_format;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'use_jspb_array_any_format' => bool,
  ) $s = shape()) {
    $this->use_jspb_array_any_format = $s['use_jspb_array_any_format'] ?? false;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "conformance.JspbEncodingConfig";
  }

  public static function ParseFrom(string $input): ?JspbEncodingConfig {
    $msg = new JspbEncodingConfig();
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
          $this->use_jspb_array_any_format = $d->readBool();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->use_jspb_array_any_format !== false) {
      $e->writeTag(1, 0);
      $e->writeBool($this->use_jspb_array_any_format);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeBool('use_jspb_array_any_format', 'useJspbArrayAnyFormat', $this->use_jspb_array_any_format, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'use_jspb_array_any_format': case 'useJspbArrayAnyFormat':
          $this->use_jspb_array_any_format = \Protobuf\Internal\JsonDecoder::readBool($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is JspbEncodingConfig)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->use_jspb_array_any_format = $o->use_jspb_array_any_format;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}


class XXX_FileDescriptor_external_com_google_protobuf_conformance_conformance__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'external/com_google_protobuf/conformance/conformance.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 2604 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\xac\x94\xcf\x6e\xda\x4a\x14\xc6\x31\x10\xfe\x1c\x48\x62\x4d\x12\xc5\xb9\x9b\x10\xee\x1f\x71\x53\x89\x54\xe9\xa6\xea\xa2\x12\xa4\x90\x90\xaa\x36\x32\x46\x69\x57\x23\x3\x3\x72\x2\x33\xee\xcc\x58\x8d\xfb\x12\x7d\xb1\x3e\x4f\xd7\x95\x67\x30\x31\x6a\x16\x5d\x74\xe7\xf3\x7d\xbf\x33\x67\xf0\xf9\x30\xbc\x21\x8f\x92\x70\xea\x2f\x2f\xa6\x6c\x85\x17\x8c\x2d\x96\x4\x87\x9c\x49\x36\x89\xe6\x17\x53\x46\xe7\x8c\xaf\x7c\x3a\x25\xd9\xe7\xb6\x2\x50\x2d\x23\x35\xff\x3\xe8\xfb\xc1\x32\xe2\x64\x44\x24\xb2\xa0\x3c\xd7\x95\x65\x34\xa\xad\xaa\x9b\x96\xcd\x1f\x5\x40\x57\x4f\x7d\x2e\xf9\x1c\x11\x21\xd1\xb\x30\xd3\xa9\x38\xf4\xe3\x25\xf3\x67\x96\xd1\x30\x5a\xf5\x9b\x9c\xbb\x9f\x3a\x43\x6d\xa0\xbf\xa1\x7e\x2f\x18\xdd\x80\xf9\x86\xd1\xaa\xde\xe4\xdc\x5a\xa2\x6e\x41\xe1\x64\x3\x95\x9f\xa0\x70\x92\x81\x24\x79\x94\x1b\xa8\x92\x42\x89\x9a\x42\xe\x1c\x73\x7d\x4d\x32\xc3\x2c\x92\x61\x24\xb1\xba\xbf\xb4\xa\xd\xa3\xb5\x77\x79\xdc\xce\xbe\x9c\xbb\x80\x93\xbe\xb2\xdd\xa3\x4d\x9f\xa3\xda\xb4\x8c\xce\xa0\xbe\x22\x42\xf8\xb\x82\x65\x1c\x12\xab\x98\x4c\x75\x6b\x6b\xcd\x8b\x43\x82\xde\xc2\xae\x24\x42\xe2\xa9\x2f\xc9\x82\xf1\xd8\xda\x51\x93\x4e\xb6\x26\x79\x44\xc8\xab\x35\xe0\xd6\x65\xa6\x42\x23\x38\x52\xbf\x9e\xd0\x29\x9b\x5\x74\x81\x59\x28\x3\x46\x85\x55\x6a\x18\xad\xda\xe5\xe9\xd6\x39\xb7\x22\x9c\xf4\xd6\x60\xb2\x9b\x60\xe1\x1e\xdc\x67\x34\x47\xf7\xa2\x97\x70\x18\xf2\x80\x4a\x1c\xd1\x7\xca\xbe\x50\x3c\xf\xc8\x72\x26\xac\x6a\xc3\x68\x55\x5c\xa4\xbc\xb1\xb6\xfa\xca\xe9\x56\xa1\xbc\x7e\xb5\xcd\xef\x79\x38\xd8\x5a\xbc\x8\x19\x15\x4\x9d\x41\x2d\xf4\xb9\x20\x98\x70\xce\xb8\x5a\x7a\xb2\x1\x50\x62\x2f\xd1\xd0\xff\xb0\x2f\x8\xf\xfc\x65\xf0\x35\xc5\x4a\x6b\x6c\x6f\x63\x68\xf4\x5f\xd8\xe5\x11\x95\xc1\x2a\x5\xd3\x6c\xd4\xd7\xb2\xc6\x9e\x8b\x5b\xe1\x77\xe3\x56\x7c\x2e\x6e\x7f\x41\x59\x3c\x4\x61\x48\x66\x6a\x55\x89\x9f\xa\x7f\x2e\x8a\xdd\xa\x94\x38\x11\xd1\x52\x36\x6d\x40\xbf\xae\xd\xbd\x86\x93\x48\x10\xac\xa6\xf9\x9c\xfb\x31\xf6\x69\x9c\x86\xd5\x50\x6b\x3a\x8a\x4\x49\x3a\x3b\x89\xdd\xa1\xb1\xce\xe4\xf9\x10\xe0\x29\xb8\x68\x1f\x6a\x63\x7b\x34\xec\x5d\xd\xfa\x83\xde\x3b\x33\x87\xea\x50\x19\xba\x8e\xe7\x74\xc7\x7d\xd3\x40\x15\x28\xde\x8e\x1c\xdb\xcc\xeb\xa7\x61\xd7\x2c\x24\x2d\x5e\xef\xa3\x87\xfb\x8e\xfb\xa1\xe3\x99\xc5\xf3\x6f\x6\xd4\xb3\x9\x45\x87\x60\x66\xe\xc5\x5e\x6f\xe4\x99\xb9\xa4\xaf\x3b\xb0\x3b\xee\x27\x2d\x18\x68\x17\xaa\xc9\xe1\xba\xcc\xa3\x7f\xa0\xa1\xca\xc1\xb5\xed\xb8\x3d\x3c\xb6\xdf\xdb\xce\x9d\x8d\x87\x1d\x77\x34\xb0\xaf\x35\x55\xd0\x4d\xc3\xae\x2e\x8b\xc9\xa8\xcc\x65\xb4\xba\xd3\x3d\x83\xd3\x29\x5b\xb5\xf5\x57\xae\x9d\xae\x39\xfb\x57\x98\x94\x94\xfa\xea\x67\x0\x0\x0\xff\xff\xb0\xdc\x95\xd8");
  }
}
