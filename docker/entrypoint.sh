#!/bin/sh
echo "DEBUG: Entrypoint started. Current directory: $(pwd)"

composer install
npm install

exec apache2-foreground
