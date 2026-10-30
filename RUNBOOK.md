# Runbook

Run the commands on the machine that runs the containers, from the project folder (when the pipeline deploys, this is the runner work folder).

## Deploy

1. Merge to `main` and wait for the pipeline jobs to be green.
2. In GitHub: Releases > Draft a new release > new tag (for example `v1.5`) > Publish.
3. The pipeline deploys and checks `/api/health` and `/api/ready`.
4. Check on the machine: `docker compose ps` and `curl -i http://localhost:8080/api/ready`.

Without the pipeline: `cp .env.example .env`, fill it, then `docker compose up --build -d --wait`.

## Rollback

Automatic: if `/api/ready` fails after a deploy, the pipeline runs `./scripts/rollback.sh HEAD~1`. The script resets the code to the previous commit, rebuilds the image and starts the stack again.

Manual:

1. Find the last good commit.
2. On the host, in the project folder: `./scripts/rollback.sh <commit>`
3. Check: `docker compose ps` and `curl -i http://localhost:8080/api/ready`

Or in GitHub, open the run of the last good release and click "Re-run all jobs".

Notes:
- The rollback does not undo database migrations. Restore a backup if the bad release changed the database.
- `HEAD~1` is the previous commit, not always the last good one. Use the commit hash if you are not sure.

## Check status

| Command | Healthy result |
|---|---|
| `docker compose ps` | mysql and nginx `healthy`, app `running` |
| `curl -i http://localhost:8080/api/health` | 200 |
| `curl -i http://localhost:8080/api/ready` | 200, `"database":"up"` |
| `docker compose exec app id` | `www-data` |

When the database is down, `/api/health` is still 200 and `/api/ready` is 503.

## Logs

```
docker compose logs --tail=100 app
docker compose logs --tail=50 nginx
docker compose logs --tail=50 mysql
docker compose logs -f app
```

## Incident response

1. Check `docker compose ps` and the two curl commands.
2. Read the logs of the service that is not healthy.
3. If it started after a release, roll back first and investigate after.
4. Fix the cause and check again.
5. Write down what happened, the cause and the fix.

Example, database down: `/api/ready` returns 503, the app log shows a database error, `docker compose ps` shows mysql stopped. After about 45 seconds nginx also becomes `unhealthy`, because its healthcheck uses `/api/ready`. Fix: `docker compose start mysql`, wait for `healthy`, check `/api/ready` again.

## Troubleshooting

- "required variable is missing": the `.env` file is missing or has empty values. Fill `APP_KEY`, `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD`.
- Every page gives 500: check `APP_KEY` is set, then read `docker compose logs app`.
- 502 Bad Gateway: the app container is down. `docker compose logs app`, then `docker compose restart app`.
- Port 8080 in use: `ss -ltnp | grep 8080`, stop that process or change `8080:80` in the compose file.
- Database login fails after changing `MYSQL_PASSWORD`: MySQL only sets the password the first time. Change it with `ALTER USER`, or recreate the volume (this deletes the data).
- Pipeline lint fails: run `vendor/bin/pint` in `src` and commit.
- Pipeline Trivy fails: update the package named in the log (`composer update`) and push again.
- Deploy step fails with `exit code 22`: `curl` got an HTTP error from `/api/health` or `/api/ready`. Run the two curl commands on the host and read the app log.
- Deploy job never starts: the self-hosted runner is offline. Start it with `./run.sh`.

## Database backup and restore

```
docker compose exec -T mysql sh -c 'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" laravel' > backup.sql
docker compose exec -T mysql sh -c 'mysql -u root -p"$MYSQL_ROOT_PASSWORD" laravel' < backup.sql
```

The first command makes a backup, the second restores it. Keep backups out of the repo.

## Secrets

Change a secret in GitHub (Settings > Secrets and variables > Actions) and publish a new release. Never put secrets, runner tokens or `.env` in the repo or in screenshots.
