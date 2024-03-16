#!/bin/bash

echo "waiting for database connection..."
until mysqladmin ping -h "$DB_HOST" --silent; do
  sleep 1
done
echo "database connection succeded"
