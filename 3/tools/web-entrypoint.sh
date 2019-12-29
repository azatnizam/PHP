#!/usr/bin/env bash

set -euo pipefail

echo "Setting permissions for the docker container..."
/tools/permission_fix.sh || true
chown -R ${DOCKER_USER}:${DOCKER_GROUP} ${VOLUME} || true
echo "Done."

exec "$@"
