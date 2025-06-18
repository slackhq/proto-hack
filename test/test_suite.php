<?hh // strict

function check(\Errors\Error $err): void {
  if (!$err->Ok()) {
    throw new Exception($err->Error());
  }
}

function a(mixed $got, mixed $exp, string $msg): void {
  if ($got != $exp) {
    throw new Exception(
      $msg.
      "; got:\n".
      print_r($got, true).
      "\n expected:\n".
      print_r($exp, true).
      "\ndiff:\n".
      diff($got, $exp),
    );
  }
}

function araw(string $got, string $exp, string $msg): void {
  if ($got === $exp) {
    return;
  }
  for ($i = 0; $i < min(strlen($got), strlen($exp)); $i++) {
    if ($got[$i] !== $exp[$i]) {
      //echo sprintf("first diff at offset:%d got:%d exp:%d\n", $i, ord($got[$i]), ord($exp[$i]));
      echo sprintf(
        "first diff at offset:%d got:%s exp:%s\n",
        $i,
        ord($got[$i]),
        ord($exp[$i]),
      );
      break;
    }
  }
  echo sprintf("length got: %d expected: %d\n", strlen($got), strlen($exp));

  $gdec = Protobuf\Internal\Decoder::FromString($got);
  $edec = Protobuf\Internal\Decoder::FromString($exp);
  while (!$gdec->isEOF() && !$edec->isEOF()) {
    list($gfn, $gwt) = $gdec->readTag();
    list($efn, $ewt) = $edec->readTag();
    echo sprintf("got fn:%d wt:%d\n", $gfn, $gwt);
    echo sprintf("exp fn:%d wt:%d\n", $efn, $ewt);
    if ($gfn != $efn || $gwt != $ewt) {
      echo "^^ mismatch ^^\n";
    }
    $gdec->skip($gfn, $gwt);
    $edec->skip($efn, $ewt);
  }
  $tmpf = tempnam('', 'proto-test-got');
  $msg .= " writing to got to $tmpf";
  file_put_contents($tmpf, $got);
  throw new Exception($msg);
}

function diff(mixed $got, mixed $exp): string {
  if (
    !is_object($got) || !is_object($exp) || get_class($got) != get_class($exp)
  ) {
    return "<not diffable>";
  }
  $rexp = new ReflectionClass($exp);
  $rgot = new ReflectionClass($got);
  foreach ($rexp->getProperties() as $prop) {
    $gotval = $prop->getValue($got);
    $expval = $rexp->getProperty($prop->name)->getValue($exp);
    if ($gotval != $expval) {
      return sprintf(
        "property: %s got: %s expected: %s",
        $prop->name,
        print_r($gotval, true),
        print_r($expval, true),
      );
    }
  }
  return "<empty diff>";
}

function repackFloat(float $f): float {
  return unpack("f", pack("f", $f))[1];
}

function testExample1(foo\bar\example1 $got, string $failmsg): void {
  $exp = new foo\bar\example1(shape(
    'adouble' => 13.37,
  ));
  $exp->afloat = repackFloat(100.1);
  $exp->aint32 = 1;
  $exp->aint64 = 12;
  $exp->auint32 = 123;
  $exp->auint64 = 1234;
  $exp->asint32 = 12345;
  $exp->asint64 = 123456;
  $exp->afixed32 = 1234567;
  $exp->afixed64 = 12345678;
  $exp->asfixed32 = 123456789;
  $exp->asfixed64 = 1234567890;
  $exp->abool = true;
  $exp->astring = "foobar";
  $exp->abytes = "hello world";

  $exp->aenum1 = foo\bar\AEnum1::B;
  $exp->aenum2 = foo\bar\example1_AEnum2::D;
  $exp->aenum22 = fizzy\bazzy\AEnum2::Z;

  $exp->manystring[] = "ms1";
  $exp->manystring[] = "ms2";
  $exp->manystring[] = "ms3";

  $exp->manyint64[] = 1;
  $exp->manyint64[] = 2;
  $exp->manyint64[] = 3;

  $e2 = new foo\bar\example1_example2();
  $exp->aexample2 = $e2;
  $e2->astring = "zomg";

  $e22 = new foo\bar\example2();
  $exp->aexample22 = $e22;
  $e22->aint32 = 123;

  $e23 = new fizzy\bazzy\example2();
  $exp->aexample23 = $e23;
  $e23->zomg = -12;

  $exp->amap["k1"] = "v1";
  $exp->amap["k2"] = "v2";

  $exp->outoforder = 1;

  $exp->aoneof = new \foo\bar\example1_aoneof_oostring("oneofstring");

  a($got, $exp, $failmsg);
}

function microtime_as_int(): int {
  $gtod = gettimeofday();
  return ($gtod['sec'] * 1000000) + $gtod['usec'];
}

function testDescriptorReflection(): void {
  $fds = Protobuf\Internal\LoadedFileDescriptors();
  $names = dict[];
  foreach ($fds as $fd) {
    $raw = $fd->FileDescriptorProtoBytes();
    if ($raw == false) {
      throw new \Exception('descriptor decode failed');
    }
    $dp = new google\protobuf\FileDescriptorProto();
    check(Protobuf\Unmarshal($raw, $dp));
    // print_r($dp);
    $names[$fd->Name()] = $raw;
  }
  if ($names['test/example1.proto'] == '') {
    throw new \Exception('missing file descriptor for example1');
  }
  $dp = new google\protobuf\FileDescriptorProto();
  check(Protobuf\Unmarshal($names['test/example1.proto'], $dp));
  if ($dp->getPackage() != 'foo.bar') {
    throw new \Exception(
      'descriptor proto for example1.proto has unexpected package: '.
      $dp->getPackage(),
    );
  }


  // TODO: We need to check reflection on optional proto3.
  // https://github.com/protocolbuffers/protobuf/blob/main/docs/implementing_proto3_presence.md#implementation-changes
}

function testReservedClassNames(): void {
  // This should run without errors.
  $c = new pb_Class();
  $i = new pb_Interface();
  $i->class = $c;
  $n = new NotClass();
}

function assert(bool $b): void {
  if (!$b) {
    throw new \Exception('assertion failed');
  }
}

function testAny(): void {
  // This should run without errors.
  $e1 = new foo\bar\example1();
  $e1->astring = "Hello World!";
  $t1 = new AnyTest();

  // Test marshaling.
  $t1->any = Protobuf\MarshalAny($e1);
  $any = $t1->any;
  invariant($any != null, "");

  \assert($any->type_url === 'type.googleapis.com/foo.bar.example1');
  \assert($any->value === Protobuf\Marshal($e1));

  // Test serde.
  $str = Protobuf\Marshal($t1);
  $t2 = new AnyTest();
  check(Protobuf\Unmarshal($str, $t2));
  $any2 = $t2->any;
  invariant($any2 != null, "");
  \assert($any2->type_url === $any->type_url);
  \assert($any2->value === $any->value);

  // Test unmarshaling
  $e2 = new foo\bar\example1();
  check(Protobuf\UnmarshalAny($any2, $e2));
  \assert($e2->astring === "Hello World!");
}

class ServerImpl implements foo\bar\ExampleServiceServer {
  public async function OneToTwo(
    \Grpc\Context $ctx,
    \foo\bar\example1 $in,
  ): Awaitable<\Errors\Result<\foo\bar\example2>> {
    if ($in->astring !== "hello") {
      throw new Exception('fail!');
    }
    return Errors\ResultV(new \foo\bar\example2(shape(
      'aint32' => 1337,
    )));
  }
}

class Context implements \Grpc\Context {
  public function IncomingMetadata(): \Grpc\Metadata {
    return \Grpc\Metadata::Empty();
  }
  public function WithTimeoutMicros(int $to): \Grpc\Context {
    return $this;
  }
  public function WithOutgoingMetadata(\Grpc\Metadata $m): \Grpc\Context {
    return $this;
  }
}

function testOptionalProto3(): void {
  echo "Testing empty optional proto3 proto: ";
  $msg = new \baz\optional_proto3();
  Protobuf\Unmarshal(
    file_get_contents('generated/test/empty_optional_proto3.pb.bin'),
    $msg,
  );
  a($msg->getAdouble(), 0., 'adouble should be set to default value');
  a($msg->hasAdouble(), false, 'adouble shoult not be set');
  a($msg->getAint64(), 0, 'aint64 should be set to default value');
  a($msg->hasAint64(), false, 'aint64 shoult not be set');
  a($msg->getAbool(), false, 'abool should be set to default value');
  a($msg->hasAbool(), false, 'abool shoult not be set');
  a($msg->getAstring(), "", 'astring should be set to default value');
  a($msg->hasAstring(), false, 'astring shoult not be set');
  a($msg->getAbytes(), "", 'abytes should be set to default value');
  a($msg->hasAbytes(), false, 'abytes shoult not be set');

  a($msg->getAnenum(), 0, 'anenum should be set to default value');
  a($msg->hasAnenum(), false, 'anenum shoult not be set');

  a($msg->getAmsg(), null, 'adouble should be set to default value');
  a($msg->hasAmsg(), false, 'adouble shoult not be set');
  a($msg->getAnany(), null, 'anany should be set to default value');
  a($msg->hasAnany(), false, 'anany shoult not be set');
  echo("PASSED\n");

  echo("Testing optional proto3 proto with only default values: ");
  $msg = new \baz\optional_proto3();
  Protobuf\Unmarshal(
    file_get_contents('generated/test/default_optional_proto3.pb.bin'),
    $msg,
  );
  a($msg->getAdouble(), 0., 'adouble should be set to default value');
  a($msg->hasAdouble(), true, 'adouble should be set');
  a($msg->getAint64(), 0, 'aint64 should be set to default value');
  a($msg->hasAint64(), true, 'aint64 should be set');
  a($msg->getAbool(), false, 'abool should be set to default value');
  a($msg->hasAbool(), true, 'abool should be set');
  a($msg->getAstring(), "", 'astring should be set to default value');
  a($msg->hasAstring(), true, 'astring should be set');
  a($msg->getAbytes(), "", 'abytes should be set to default value');
  a($msg->hasAbytes(), true, 'abytes should be set');

  a($msg->getAnenum(), 0, 'anenum should be set to default value');
  a($msg->hasAnenum(), true, 'anenum should be set');

  a($msg->getAmsg()?->astring, "", 'amsg should be set to default value');
  a($msg->hasAmsg(), true, 'amsg should be set');
  a($msg->getAnany()?->value, "", 'anany should be set to default value');
  a($msg->hasAnany(), true, 'anany should be set');
  echo("PASSED\n");

  echo("Testing optional proto3 proto with only custom non-default values: ");
  $msg = new \baz\optional_proto3();
  Protobuf\Unmarshal(
    file_get_contents('generated/test/custom_optional_proto3.pb.bin'),
    $msg,
  );
  a($msg->getAdouble(), 3.14, 'adouble should be set');
  a($msg->hasAdouble(), true, 'adouble should be set');
  a($msg->getAint64(), 1234, 'aint64 should be set');
  a($msg->hasAint64(), true, 'aint64 should be set');
  a($msg->getAbool(), true, 'abool should be set');
  a($msg->hasAbool(), true, 'abool should be set');
  a($msg->getAstring(), "string", 'astring should be set');
  a($msg->hasAstring(), true, 'astring should be set');
  a($msg->getAbytes(), "bytes", 'abytes should be set');
  a($msg->hasAbytes(), true, 'abytes should be set');

  a($msg->getAnenum(), 10, 'anenum should be set');
  a($msg->hasAnenum(), true, 'anenum should be set');

  a($msg->getAmsg()?->astring, "inner_string", 'amsg should be set');
  a($msg->hasAmsg(), true, 'amsg should be set');
  a($msg->getAnany()?->value, "any_bytes", 'anany should be set');
  a($msg->hasAnany(), true, 'anany should be set');
  echo("PASSED\n");

  echo("Testing JSON decoding: ");
  $msg = new \baz\optional_proto3();
  Protobuf\UnmarshalJson(
    file_get_contents('test/mixed_optional_proto3.pb.json'),
    $msg,
  );
  a($msg->getAdouble(), 3.14, 'adouble should be set');
  a($msg->hasAdouble(), true, 'adouble should be set');
  a($msg->getAint64(), 1234, 'aint64 should be set');
  a($msg->hasAint64(), true, 'aint64 should be set');
  a($msg->getAbool(), false, 'abool should be set');
  a($msg->hasAbool(), true, 'abool should be set');
  a($msg->getAstring(), "", 'astring should be set');
  a($msg->hasAstring(), false, 'astring should be set');
  // TODO: We are storing some bad bytes during JSON decoding.
  //a($msg->getAbytes(), "bytes", 'abytes should be set');
  a($msg->hasAbytes(), true, 'abytes should be set');

  a($msg->getAnenum(), 10, 'anenum should be set');
  a($msg->hasAnenum(), true, 'anenum should be set');

  a($msg->getAmsg()?->astring, "inner_string", 'amsg should be set');
  a($msg->hasAmsg(), true, 'amsg should be set');
  // TODO: We are storing some bad bytes during JSON decoding.
  //a($msg->getAnany()->value, "any_bytes", 'anany should be set');
  a($msg->hasAnany(), true, 'anany should be set');
  echo("PASSED\n");

  echo("Testing manual manipulation of protos:");
  // For a few fields, we set it to the default value then a custom one.
  $msg = new \baz\optional_proto3();
  a($msg->hasAdouble(), false, 'adouble should not be set');
  a($msg->getAdouble(), 0., 'adouble should be set to default value');
  $msg->setAdouble(0.);
  a($msg->getAdouble(), 0., 'adouble should be set');
  a($msg->hasAdouble(), true, 'adouble should be set');
  $msg = new \baz\optional_proto3();
  $msg->setAdouble(6.29);
  a($msg->getAdouble(), 6.29, 'adouble should be set');
  a($msg->hasAdouble(), true, 'adouble should be set');

  a($msg->hasAstring(), false, 'astring should not be set');
  a($msg->getAstring(), "", 'astring should not be set');
  $msg->setAstring("");
  a($msg->hasAstring(), true, 'astring should be set');
  a($msg->getAstring(), "", 'astring should be set');
  $msg = new \baz\optional_proto3();
  $msg->setAstring("string_string");
  a($msg->hasAstring(), true, 'astring should be set');
  a($msg->getAstring(), "string_string", 'astring should be set');

  a($msg->hasAmsg(), false, 'amsg should not be set');
  a($msg->getAmsg(), null, 'amsg should be set to default value');
  $msg->setAmsg(null);
  a($msg->hasAmsg(), true, 'amsg should be set');
  a($msg->getAmsg(), null, 'amsg should be set');
  $msg = new \baz\optional_proto3();
  $inner_msg = new \baz\optional_proto3_InnerMsg();
  $inner_msg->astring = 'inner_string';
  $msg->setAmsg($inner_msg);
  a($msg->hasAmsg(), true, 'amsg should be set');
  a($msg->getAmsg()?->astring, 'inner_string', 'amsg should be set');
  echo("PASSED\n");
}

function testLoopbackService(): void {
  $cli = new \foo\bar\ExampleServiceClient(new Grpc\LoopbackInvoker(
    \foo\bar\ExampleServiceServiceDescriptor(new ServerImpl()),
  ));
  $in = new \foo\bar\example1(shape(
    'astring' => 'hello',
  ));
  $out = HH\Asio\join($cli->OneToTwo(new Context(), $in));
  if ($out->MustValue()->aint32 !== 1337) {
    throw new Exception('loopback service test failed');
  }
}

function stddev(vec<num> $values, float $avg): float {
  $std = 0.;
  foreach ($values as $value) {
    $d = $value - $avg;
    $std += $d * $d;
  }
  return \HH\Lib\Math\sqrt($std / \HH\Lib\C\count($values));
}

function bench(int $runs): void {
  $raw = file_get_contents('generated/test/example1.pb.bin');
  $iter = 100000;

  echo "Running benchmark {$runs} times\n";
  // We ignore the warm-up run as it would skew the data.
  echo "But first, let's warm up HHVM to ensure consistent measures\n";
  for ($i = 0; $i < $iter; $i++) {
    $message = new foo\bar\example1();
    check(Protobuf\Unmarshal($raw, $message));
    Protobuf\Marshal($message);
  }

  $durations = vec[];
  for ($j = 0; $j < $runs; $j++) {
    $duration = clock_gettime_ns(CLOCK_REALTIME);
    for ($i = 0; $i < $iter; $i++) {
      $message = new foo\bar\example1();
      check(Protobuf\Unmarshal($raw, $message));
      Protobuf\Marshal($message);
    }
    $duration = (clock_gettime_ns(CLOCK_REALTIME) - $duration) / 1000000;
    echo "$iter iterations in $duration (ms)\n";
    $durations[] = $duration;
  }

  if (!\HH\Lib\C\is_empty($durations)) {
    $avg = \HH\Lib\Math\mean($durations) as nonnull;
    $std = stddev($durations, $avg);
    echo "----------------\n";
    echo "Average $avg (ms)\n";
    echo "Stddev $std (ms)\n";
    echo "----------------\n";
  }
}
