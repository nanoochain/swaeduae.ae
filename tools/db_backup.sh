#!/usr/bin/env bash
set -euo pipefail
cd /home3/vminingc/swaeduae.ae/laravel-app
: "${DB_HOST:=$(php -r 'echo parse_url(getenv("DB_HOST")?: "localhost", PHP_URL_HOST)?:getenv("DB_HOST");')}"
: "${DB_DATABASE:=$(grep -E '^DB_DATABASE=' .env | cut -d= -f2)}"
: "${DB_USERNAME:=$(grep -E '^DB_USERNAME=' .env | cut -d= -f2)}"
: "${DB_PASSWORD:=$(grep -E '^DB_PASSWORD=' .env | cut -d= -f2)}"

ts=$(date +%F-%H%M%S)
out="storage/backups"
mkdir -p "$out"
file="$out/db-$DB_DATABASE-$ts.sql.gz"

mysqldump --no-tablespaces -h "$DB_HOST" -u "$DB_USERNAME" -p"$DB_PASSWORD" --single-transaction --quick --skip-extended-insert "$DB_DATABASE" \
  | gzip -c > "$file"

# keep 14 days
find "$out" -name 'db-*.sql.gz' -mtime +14 -delete
echo "[OK] backup: $file"
