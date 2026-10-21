#!/bin/bash

echo "waiting for database connection..."
until mysqladmin ping -h "$DB_HOST" --ssl-verify-server-cert=off; do
  sleep 5
done
echo "database connection succeded"
