#!/bin/sh

set -xe

echo "Running the typechecker"
hh_client .

echo "Running other tests"
# TODO: Replace those with //... once //:typechecker_test is fixed.
bazel test //:gen_test
bazel test //:library_test
bazel test //:integration_test
bazel test //:conformance_test
