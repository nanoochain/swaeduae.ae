#!/usr/bin/env bash
set -euo pipefail
BASE="${1:?Usage: form_probe.sh https://site/path [--bot] }"
BOT="${2:-}"
JAR=$(mktemp); HTML=$(mktemp)
FINAL=$(curl -sS -L -c "$JAR" -b "$JAR" -o "$HTML" -w '%{url_effective}' "$BASE")
TOKEN=$(grep -oP 'name="_token"\s+value="\K[^"]+' "$HTML" || true)
ACTION=$(grep -oP '<form[^>]+action="\K[^"]+' "$HTML" | head -n1 || true)
case "$ACTION" in http*://*) POST_URL="$ACTION" ;; /*) POST_URL="$(echo "$FINAL" | sed 's#^\(https\?://[^/]*\).*#\1#')$ACTION" ;; *) POST_URL="$FINAL" ;; esac
XSRF_RAW=$(awk -F'\t' '$6=="XSRF-TOKEN"{print $7}' "$JAR" || true)
XSRF=$(php -r 'echo urldecode($argv[1]??"");' "$XSRF_RAW")

echo "FINAL=$FINAL"
echo "POST_URL=$POST_URL"
echo "_token.len=${#TOKEN}  XSRF.len=${#XSRF}"

ARGS=( --data-urlencode "_token=$TOKEN"
       --data-urlencode "org_name=Probe Org"
       --data-urlencode "email=probe@example.com" )

if [ "$BOT" = "--bot" ]; then
  ARGS+=( -d "_hp=bot" -d "_hpt=$(date +%s)" )
fi

curl -sS -o /dev/null -w "HTTP:%{http_code}\n" -b "$JAR" -H "X-CSRF-TOKEN: $XSRF" -e "$FINAL" -X POST "$POST_URL" "${ARGS[@]}"
