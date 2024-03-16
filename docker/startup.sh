#!/bin/bash

echo "mode: $MODE"
if [ "$MODE" = "updater" ]; then
	/wait-for-mysql.sh && \
	/import-struct.sh && \
	/run-updater.sh
else
	apache2-foreground
fi
