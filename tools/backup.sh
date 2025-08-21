#!/usr/bin/env bash
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"; cd "$ROOT"
PHP_BIN="${PHP_BIN:-/opt/alt/php84/usr/bin/php}"; [ -x "$PHP_BIN" ] || PHP_BIN="php"
OUT="$ROOT/storage/backups"; mkdir -p "$OUT"
readarray -t KV < <("$PHP_BIN" -r '
require "vendor/autoload.php"; $app=require "bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$c = config("database.connections.mysql");
printf("H=%s\nP=%s\nD=%s\nU=%s\nW=%s\n", $c["host"], $c["port"]??3306, $c["database"], $c["username"], $c["password"]);
')
for line in "${KV[@]}"; do eval "$line"; done
STAMP="$(date +%F_%H%M%S)"
SQL="$OUT/db-$D-$STAMP.sql.gz"
FILES="$OUT/storage-$STAMP.tgz"
mysqldump -h "$H" -P "$P" -u "$U" -p"$W" --single-transaction --quick --create-options "$D" | gzip -c > "$SQL"
tar -czf "$FILES" -C "$ROOT" public/storage
find "$OUT" -type f -mtime +7 -delete
echo "BACKUP OK: $SQL and $FILES"
