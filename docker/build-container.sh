#!/bin/bash

# run `docker buildx create --use` before first use
docker buildx build --platform linux/amd64,linux/arm64 -t mazdermind/replicate-sequences:latest -f ./Dockerfile ..
docker tag mazdermind/replicate-sequences:latest mazdermind/replicate-sequences:v2
