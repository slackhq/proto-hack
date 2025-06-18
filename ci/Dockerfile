FROM hhvm/hhvm:4.153-latest

RUN curl -fsSL https://bazel.build/bazel-release.pub.gpg | gpg --dearmor > /usr/share/keyrings/bazel-archive-keyring.gpg
RUN echo "deb [arch=amd64 signed-by=/usr/share/keyrings/bazel-archive-keyring.gpg] https://storage.googleapis.com/bazel-apt stable jdk1.8" > /etc/apt/sources.list.d/bazel.list
RUN apt update && apt install -y bazel bazel-5.4.0 golang
RUN export USE_BAZEL_VERSION=5.4.0
