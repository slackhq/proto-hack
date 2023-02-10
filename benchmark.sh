#!/bin/bash
set -xeuo pipefail
echo running benchmark harness with repo auth

runs=${1:-}
TMP=`mktemp -d`
hhvm --hphp -t hhbc --module=generated --module=lib --module=test --output-dir $TMP
hhvm -d hhvm.repo.authoritative=true -d hhvm.repo.central.path=$TMP/hhvm.hhbc test/test.php bench $runs
