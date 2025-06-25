load("@bazel_tools//tools/build_defs/repo:http.bzl", "http_archive")

http_archive(
  name = "com_google_googletest",
  urls = ["https://github.com/google/googletest/archive/2a02723b76eae879bafbcee5143d5104a13c3626/.zip"],
  strip_prefix = "googletest-2a02723b76eae879bafbcee5143d5104a13c3626/",
)

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

PBV = "25.3"

http_archive(
    name = "com_google_protobuf",
    sha256 = "5156b22536feaa88cf95503153a6b2cd67cc80f20f1218f154b84a12c288a220",
    strip_prefix = "protobuf-" + PBV,
    urls = ["https://github.com/protocolbuffers/protobuf/archive/refs/tags/v" + PBV + ".zip"],
)

load("@com_google_protobuf//:protobuf_deps.bzl", "protobuf_deps")

protobuf_deps()
