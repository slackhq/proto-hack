#!/bin/bash
set -euo pipefail
#ls -R

#
# Setup a tmp directory
#
TMP=`mktemp -d /tmp/webapp-protobuf-gen.XXXXXXXXXX`
SUCCESS=0
function fin() {
  rm -rf $TMP
  echo
  if [ "$SUCCESS" -eq "1" ]; then
    echo -e "\033[1;32mSUCCESS\033[0m"
  else
    echo -e "\033[1;31mFAILURE\033[0m"
  fi
}
trap fin EXIT



GENHACK=`find -L . -name protoc-gen-hack | head -1`
GENHACK=`readlink $GENHACK`
echo genhack path: $GENHACK
$GENHACK --version

PROTOC=`find -L . external -name protoc | head -1`
PROTOC=`readlink $PROTOC`
echo protoc path: $PROTOC
$PROTOC --version

# For some reason, test_messages_proto{2,3}.proto are in virtual_imports,
# despite an explicit dependency on those in the BUILD file :'-(
# TODO: We should figure out a better way of handling this.
cp external/com_google_protobuf/_virtual_imports/test_messages_proto2_proto/google/protobuf/test_messages_proto2.proto external/com_google_protobuf/src/google/protobuf/test_messages_proto2.proto
cp external/com_google_protobuf/_virtual_imports/test_messages_proto3_proto/google/protobuf/test_messages_proto3.proto external/com_google_protobuf/src/google/protobuf/test_messages_proto3.proto

PBS=`find . -name '*.proto' | grep -v _virtual_imports`

ARGS="-I external/com_google_protobuf/src -I ./"

echo
echo generating hacklang...
for SRC in $PBS
do
  echo source: $SRC
  $PROTOC $ARGS --plugin=$GENHACK --hack_out="plugin=grpc,allow_proto2_dangerous:$TMP" --experimental_allow_proto3_optional $SRC
  echo
done

$PROTOC $ARGS --encode=foo.bar.example1  ./test/example1.proto < ./test/example1.pb.txt > $TMP/test/example1.pb.bin
$PROTOC $ARGS --encode=baz.optional_proto3  ./test/optional_proto3.proto < ./test/empty_optional_proto3.pb.txt > $TMP/test/empty_optional_proto3.pb.bin
$PROTOC $ARGS --encode=baz.optional_proto3  ./test/optional_proto3.proto < ./test/default_optional_proto3.pb.txt > $TMP/test/default_optional_proto3.pb.bin
$PROTOC $ARGS --encode=baz.optional_proto3  ./test/optional_proto3.proto < ./test/custom_optional_proto3.pb.txt > $TMP/test/custom_optional_proto3.pb.bin

if [ $# -gt 0 ]; then
  # Comparison mode; see if there are diffs, if none, exit 0.
  echo
  echo "comparing outputs with destination"
  diff -r $TMP ./generated
else
  DST="${BUILD_WORKSPACE_DIRECTORY}/generated"
  echo
  echo "copying outputs to destination"
  rm -r $DST
  cp -r $TMP $DST
fi

SUCCESS=1
