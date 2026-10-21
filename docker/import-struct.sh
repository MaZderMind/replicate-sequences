#!/bin/bash

echo "creating db structure"
export MYSQL_PWD="$DB_PASSWORD"
mysql -h "$DB_HOST" -u "$DB_USER" "$DB_NAME" --ssl-verify-server-cert=off </var/www/html/struct.sql
