<?hh // strict
namespace fizzy\bazzy;

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// Source: test/example2.proto

newtype AEnum2_enum_t as int = int;
abstract class AEnum2 {
  const AEnum2_enum_t Z = 0;
  private static dict<int, string> $itos = dict[
    0 => 'Z',
  ];
  public static function ToStringDict(): dict<int, string> {
    return self::$itos;
  }
  private static dict<string, int> $stoi = dict[
    'Z' => 0,
  ];
  public static function FromMixed(mixed $m): AEnum2_enum_t {
    if ($m is string) return idx(self::$stoi, $m, \is_numeric($m) ? ((int) $m) : 0);
    if ($m is int) return $m;
    return 0;
  }
  public static function FromInt(int $i): AEnum2_enum_t {
    return $i;
  }
}

class example2 implements \Protobuf\Message {
  public int $zomg;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'zomg' => int,
  ) $s = shape()) {
    $this->zomg = $s['zomg'] ?? 0;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "fiz.baz.example2";
  }

  public static function ParseFrom(string $input): ?example2 {
    $msg = new example2();
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
          $this->zomg = $d->readVarint32Signed();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->zomg !== 0) {
      $e->writeTag(1, 0);
      $e->writeVarint($this->zomg);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeInt32('zomg', 'zomg', $this->zomg, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'zomg':
          $this->zomg = \Protobuf\Internal\JsonDecoder::readInt32Signed($v);
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
    $this->zomg = $o->zomg;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class refexample3 implements \Protobuf\Message {
  public ?\Funky $funky;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'funky' => ?\Funky,
  ) $s = shape()) {
    $this->funky = $s['funky'] ?? null;
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "fiz.baz.refexample3";
  }

  public static function ParseFrom(string $input): ?refexample3 {
    $msg = new refexample3();
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
          if ($this->funky is null) {
            $this->funky = new \Funky();
          }
          $this->funky->MergeFrom($d->readDecoder());
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    $msg = $this->funky;
    if ($msg != null) {
      $nested = new \Protobuf\Internal\Encoder();
      $msg->WriteTo($nested);
      $e->writeEncoder($nested, 1);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeMessage('funky', 'funky', $this->funky, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'funky':
          if ($v is null) break;
          if ($this->funky is null) {
            $this->funky = new \Funky();
          }
          $this->funky->MergeJsonFrom($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is refexample3)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $tmp = $o->funky;
    if ($tmp is nonnull) {
      $nv = new \Funky();
      $nv->CopyFrom($tmp);
      $this->funky = $nv;
    }
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class MyRequest implements \Protobuf\Message {
  public string $name;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'name' => string,
  ) $s = shape()) {
    $this->name = $s['name'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "fiz.baz.MyRequest";
  }

  public static function ParseFrom(string $input): ?MyRequest {
    $msg = new MyRequest();
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
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('name', 'name', $this->name, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'name':
          $this->name = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is MyRequest)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->name = $o->name;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class MyResponse implements \Protobuf\Message {
  public string $message;
  private string $XXX_unrecognized;

  public function __construct(shape(
    ?'message' => string,
  ) $s = shape()) {
    $this->message = $s['message'] ?? '';
    $this->XXX_unrecognized = '';
  }

  public function MessageName(): string {
    return "fiz.baz.MyResponse";
  }

  public static function ParseFrom(string $input): ?MyResponse {
    $msg = new MyResponse();
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
          $this->message = $d->readString();
          break;
        default:
          $d->skip($fn, $wt);
      }
    }
    $this->XXX_unrecognized = $d->skippedRaw();
  }

  public function WriteTo(\Protobuf\Internal\Encoder $e): void {
    if ($this->message !== '') {
      $e->writeTag(1, 2);
      $e->writeString($this->message);
    }
    $e->writeRaw($this->XXX_unrecognized);
  }

  public function WriteJsonTo(\Protobuf\Internal\JsonEncoder $e): void {
    $e->writeString('message', 'message', $this->message, false);
  }

  public function MergeJsonFrom(mixed $m): void {
    if ($m === null) return;
    $d = \Protobuf\Internal\JsonDecoder::readObject($m);
    foreach ($d as $k => $v) {
      switch ($k) {
        case 'message':
          $this->message = \Protobuf\Internal\JsonDecoder::readString($v);
          break;
        default:
        break;
      }
    }
  }

  public function CopyFrom(\Protobuf\Message $o): \Errors\Error {
    if (!($o is MyResponse)) {
      return \Errors\Errorf('CopyFrom failed: incorrect type received: %s', $o->MessageName());
    }
    $this->message = $o->message;
    $this->XXX_unrecognized = $o->XXX_unrecognized;
    return \Errors\Ok();
  }
}

class MyServiceClient {
  public function __construct(private \Grpc\Invoker $invoker) {
  }

  public async function MyMethod(\Grpc\Context $ctx, \fizzy\bazzy\MyRequest $in, \Grpc\CallOption ...$co): Awaitable<\Errors\Result<\fizzy\bazzy\MyResponse>> {
    $out = new \fizzy\bazzy\MyResponse();
    $err = await $this->invoker->Invoke($ctx, '/fiz.baz.MyService/MyMethod', $in, $out, ...$co);
    if ($err->Ok()) {
      return \Errors\ResultV($out);
    }
    return \Errors\ResultE($err);
  }
}

interface MyServiceServer {
  public function MyMethod(\Grpc\Context $ctx, \fizzy\bazzy\MyRequest $in): Awaitable<\Errors\Result<\fizzy\bazzy\MyResponse>>;
}

function MyServiceServiceDescriptor(MyServiceServer $service): \Grpc\ServiceDesc {
  $methods = vec[];
  $handler = async (\Grpc\Context $ctx, \Grpc\Unmarshaller $u): Awaitable<\Errors\Result<\Protobuf\Message>> ==> {
    $in = new \fizzy\bazzy\MyRequest();
    $err = $u->Unmarshal($in);
    if (!$err->Ok()) {
      return \Errors\ResultE(\Errors\Errorf('proto unmarshal: %s', $err->Error()));
    }
    return (await $service->MyMethod($ctx, $in))->As<\Protobuf\Message>();
  };
  $methods []= new \Grpc\MethodDesc('MyMethod', $handler);
  return new \Grpc\ServiceDesc('fiz.baz.MyService', $methods);
}

function RegisterMyServiceServer(\Grpc\Server $server, MyServiceServer $service): void {
  $server->RegisterService(MyServiceServiceDescriptor($service));
}

class XXX_FileDescriptor_test_example2__proto implements \Protobuf\Internal\FileDescriptor {
  const string NAME = 'test/example2.proto';
  public function Name(): string {
    return self::NAME;
  }

  public function FileDescriptorProtoBytes(): string {
    // 992 bytes of gzipped FileDescriptorProto as a string
    return (string)\gzuncompress("\x78\xda\x54\x90\x4f\x4a\xc3\x40\x14\xc6\xd\x98\xa4\x7d\x59\xa8\xd3\x4d\x9\x45\x25\xb\x11\x85\x14\x92\xb\xa8\xa0\xbb\x6c\xe2\x4e\x84\x32\xa9\x2f\x6d\xa8\xf3\xc7\xcc\x44\x9c\xd9\x7a\x29\xcf\xe0\x61\x3c\x83\x64\x62\x84\xee\xbe\xf7\xbe\x1f\xbf\xc7\xc\xcc\x34\x2a\xbd\xc4\xf\xca\xe4\x2b\x66\xa9\x6c\x85\x16\x24\xac\x1b\x9b\x56\xd4\xc6\x7b\x6d\x3e\xb4\xf1\x42\x48\xbd\x74\x71\xb5\xa5\xeb\xdd\x4a\x48\xdd\x8\xae\x86\x36\x39\x85\xc9\x68\x23\x4\xe\xad\x60\x9b\xb9\x77\xee\x5d\xfa\xa5\xcb\xc9\x35\x44\x2d\xd6\xa3\x92\x2c\xc0\xaf\x3b\xbe\x33\x8e\x89\xb2\x20\x7d\xe8\xa7\x72\x58\x26\x67\x30\x2d\x4c\x89\x6f\x1d\x2a\xdd\xdb\x38\x65\xe8\xc8\x69\xe9\x72\x72\x1\xd0\x3\x4a\xa\xae\x90\xcc\x21\x64\xa8\x14\xdd\x8c\xd0\x38\x5e\x1d\x41\x70\x7b\xcf\x3b\x96\x11\x1f\xbc\xa7\xe3\x83\xec\xa6\x37\x3f\x62\xfb\xde\xac\x91\xe4\x30\x29\x4c\x81\x7a\x2b\x5e\x8\x49\xff\x1e\x9f\xfe\x5f\x8e\x67\x7b\xbb\xe1\xd8\xdd\xc9\xf7\xcf\xd7\x67\x18\xd5\x8d\xb5\xe6\xb9\xa2\xd6\x9a\x2a\x70\x5f\x90\xff\x6\x0\x0\xff\xff\xfc\x82\x69\x18");
  }
}
