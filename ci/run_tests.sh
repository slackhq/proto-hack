#!/bin/sh

sanity_check=$(git diff-index HEAD)
if [ -n "$sanity_check" ]; then
  echo "There is already a \`git diff\`!!\nBailing out as we can't detect any formatting change"
  exit 1
fi

set -xe

echo "Checking go fmt"
go fmt protoc-gen-hack/*.go
if [ -n "$(git diff-index HEAD)" ]; then
  echo "Please run and commit the result of:\n  go fmt protoc-gen-hack/*.go"
  exit 1
fi

echo "Checking hackfmt"
FILES=`find . | grep -E "\.(php|hack)$" | grep -v \.swp | grep -v '^./generated/.*'`
for i in $FILES
do
  hackfmt -i $i
  if [ -n "$(git diff-index HEAD)" ]; then
    echo "Please run and commit the result of:\n  hackfmt -i $i"
    exit 1
  fi
done

echo "Running the typechecker"
hh_client .

echo "Running other tests"
# TODO: Replace those with //... once //:typechecker_test is fixed.
bazel test //:gen_test
bazel test //:library_test
bazel test //:integration_test
bazel test //:conformance_test
