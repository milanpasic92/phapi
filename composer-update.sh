#!/usr/bin/env bash

docker container run --rm -v $(pwd):/app/ composer:2.0 update --prefer-dist --ignore-platform-reqs --working-dir=/app

echo "--- All Done. Happy coding :) ---\n"