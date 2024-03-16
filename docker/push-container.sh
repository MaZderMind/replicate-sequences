#!/bin/bash

# run `docker buildx create --use` before first use
docker buildx build --platform linux/amd64,linux/arm64 -t mazdermind/replicate-sequences:latest --push -f ./Dockerfile ..
docker buildx build --platform linux/amd64,linux/arm64 -t mazdermind/replicate-sequences:v2 --push -f ./Dockerfile ..
