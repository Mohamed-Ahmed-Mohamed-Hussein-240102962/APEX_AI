#!/bin/bash
set -e

TARGET_COMMIT=${1:-HEAD~1}

git reset --hard "$TARGET_COMMIT"

docker compose down
docker compose build --no-cache
docker compose up -d --wait

docker compose exec -T app php artisan config:clear
docker compose exec -T app php artisan cache:clear