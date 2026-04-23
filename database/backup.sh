#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
CONFIG_FILE="$ROOT_DIR/config/config.php"
BACKUP_DIR="${1:-$ROOT_DIR/database/backups}"

if [[ ! -f "$CONFIG_FILE" ]]; then
  echo "No se encontro config/config.php" >&2
  exit 1
fi

if ! command -v mysqldump >/dev/null 2>&1; then
  echo "mysqldump no esta disponible en este entorno." >&2
  exit 1
fi

read_config() {
  local key="$1"
  php -r '$c=require $argv[1]; $k=$argv[2]; echo $c["db"][$k] ?? "";' "$CONFIG_FILE" "$key"
}

DB_HOST="${DB_HOST:-$(read_config host)}"
DB_PORT="${DB_PORT:-$(read_config port)}"
DB_NAME="${DB_NAME:-$(read_config dbname)}"
DB_USER="${DB_USER:-$(read_config user)}"
DB_PASS="${DB_PASS:-$(read_config pass)}"

if [[ -z "$DB_HOST" || -z "$DB_PORT" || -z "$DB_NAME" || -z "$DB_USER" ]]; then
  echo "Faltan datos de conexion a base de datos." >&2
  exit 1
fi

mkdir -p "$BACKUP_DIR"
TIMESTAMP="$(date +%Y-%m-%d_%H-%M-%S)"
OUTPUT_FILE="$BACKUP_DIR/${DB_NAME}_${TIMESTAMP}.sql"

MYSQL_PWD="$DB_PASS" mysqldump \
  -h "$DB_HOST" \
  -P "$DB_PORT" \
  -u "$DB_USER" \
  --single-transaction \
  --routines \
  --triggers \
  "$DB_NAME" > "$OUTPUT_FILE"

gzip -f "$OUTPUT_FILE"

echo "Backup generado: ${OUTPUT_FILE}.gz"
