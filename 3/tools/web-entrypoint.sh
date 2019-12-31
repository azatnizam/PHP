#!/usr/bin/env bash

set -euo pipefail

: "${DC_USER_ID:=}"
: "${DC_GROUP_ID:=}"
: "${BACKEND_HOST_PORT:=9000}"

if [[ -d /etc/nginx/sites-includes.ro ]] ; then
    rm -rf /etc/nginx/sites-includes
    cp -r /etc/nginx/sites-includes.ro /etc/nginx/sites-includes
fi
if [[ -d /etc/nginx/sites-enabled.ro ]] ; then
    rm -rf /etc/nginx/sites-enabled
    cp -r /etc/nginx/sites-enabled.ro /etc/nginx/sites-enabled
fi

while IFS= read -r filePath ; do
    [[ -n "$filePath" ]] || continue
    sed -i -e "s@%RUN_DIR%@${DIR_RUN_ENV}@g" "$filePath"
    sed -i -e "s@%PROJECTS_USERNAME%@${WEB_USERNAME_ENV}@g" "$filePath"
    sed -i -e "s@%MAPPING_PORT_80%@${UNPRIVILEGUE_PORT_MAPPING_80_ENV}@g" "$filePath"
    sed -i -e "s@%MAPPING_PORT_443%@${UNPRIVILEGUE_PORT_MAPPING_443_ENV}@g" "$filePath"

#    sed -i "s|backend:9000|${BACKEND_HOST_NAME}:${BACKEND_HOST_PORT}|g" "$filePath"
#    sed -i "s|/var/log/nginx|${NGINX_LOGS_DIR}|g" "$filePath"
#    sed -i "s|/var/log/php|${PHP_LOGS_DIR}|g" "$filePath"
#    sed -i -r "s|^(\s*)listen\s+80\s*|\1listen $NGINX_LISTEN_PORT |g" "$filePath"
#    sed -i -r "s|^(\s*)listen\s+\[::\]:80\s*|\1listen [::]:$NGINX_LISTEN_PORT |g" "$filePath"
done <<< "$(find /etc/nginx/sites-includes -type f -name '*.conf' || find /etc/nginx/sites-enabled -type f -name '*.conf')"

echo "Setting permissions for the docker container..."
/tools/permission_fix.sh || true
chown -R ${DOCKER_USER}:${DOCKER_GROUP} ${VOLUME} || true
echo "Done."

exec "$@"
