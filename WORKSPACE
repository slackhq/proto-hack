load("@bazel_tools//tools/build_defs/repo:http.bzl", "http_archive")

http_archive(
    name = "io_bazel_rules_go",
    sha256 = "f74c98d6df55217a36859c74b460e774abc0410a47cc100d822be34d5f990f16",
    urls = [
        "https://mirror.bazel.build/github.com/bazelbuild/rules_go/releases/download/v0.47.1/rules_go-v0.47.1.zip",
        "https://github.com/bazelbuild/rules_go/releases/download/v0.47.1/rules_go-v0.47.1.zip",
    ],
)

load("@io_bazel_rules_go//go:deps.bzl", "go_register_toolchains", "go_rules_dependencies")

go_rules_dependencies()

go_register_toolchains(version = "1.22.2")

PBV = "21.12"

http_archive(
    name = "com_google_protobuf",
    sha256 = "6a31b662deaeb0ac35e6287bda2f3369b19836e6c9f8828d4da444346f420298",
    strip_prefix = "protobuf-" + PBV,
    urls = ["https://github.com/protocolbuffers/protobuf/archive/refs/tags/v" + PBV + ".zip"],
)

load("@com_google_protobuf//:protobuf_deps.bzl", "protobuf_deps")

protobuf_deps()
