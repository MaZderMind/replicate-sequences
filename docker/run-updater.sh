#!/bin/bash

cd /var/www/html/
while true; do
	php update.php
	sleep 120
done
