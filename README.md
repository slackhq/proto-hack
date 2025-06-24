# proto-hack
Hacklang generator for protobuf

# Dependencies
- bazel[isk] which will manage:
  - protoc
  - golang toolchain
- hhvm

# How to use the plugin locally
Run `bazel build protoc-gen-hack` to build the plugin binary; Then run

```
protoc --proto_path=/path/to/proto/folder --plugin=/path/to/protoc-gen-hack --hack_out=plugin=grpc:/path/to/output/folder <proto/files>
```

To generate multiple files per proto based on top level entities, please add the following flag

```
--hack_out=plugin=grpc,file_per_entity:/path/to/output
```

# Usage
`protoc --hack_out=./gen-src example.proto`

To include gRPC service stubs:
`protoc --hack_out=plugin=grpc:./gen-src example.proto`

In addition to generated code, you will need the library code in `/lib`.

```
  $msg = new ExampleMessage();
  $raw = Protobuf\Marshal($msg);
  $msg2 = new ExampleMessage(); 
  Protobuf\Unmarshal($raw, $msg);
```

# Development
## Build the docker image
```
cd ci && docker build -t proto-hack-dev .
```

## Mount the Docker container
```
# In the root folder
docker run -it --rm -v "$(pwd):/workspace" -w /workspace proto-hack-dev /bin/bash
```

## In the Docker Image
testing: `bazelisk test //...`
codegen: `bazelisk run :gen`

# Notes
- Unsigned 64 bit integer types (e.g. uint64, fixed64) are represented using
their signed counterparts, with the top bit simply being stored in the sign bit.
(similar to java).

# Recommendations
- Avoid unsigned 64 bit types (uint64, fixed64)
