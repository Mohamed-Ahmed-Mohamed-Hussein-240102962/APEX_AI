# Library API: Docker, CI/CD and rollback

My solution for the Apex AI Arabia Junior DevOps assessment. The service is a Laravel library API (Nginx, PHP-FPM, MySQL). It runs with Docker Compose, and a GitHub Actions pipeline tests it, scans it, deploys it on a self-hosted runner and rolls back if the new version is not healthy.

Other files: `RUNBOOK.md` (operations) and `EVIDENCE.pdf` (screenshots).

## Run it locally

You need Docker and Docker Compose v2.

1. `git clone <repo-url>` and `cd` into it
2. `cp .env.example .env`
3. Fill the 3 values in `.env`:
   - `APP_KEY`: run `echo "base64:$(openssl rand -base64 32)"`
   - `MYSQL_PASSWORD` and `MYSQL_ROOT_PASSWORD`: any passwords
4. `docker compose up --build -d`
5. `curl -i http://localhost:8080/api/health` and `curl -i http://localhost:8080/api/ready`
6. Open http://localhost:8080

On first start the app runs the migrations and creates 3 demo users (admin, librarian, member, `example.com` emails). Passwords are in `src/database/seeders/DemoUsersSeeder.php`. They are fake demo accounts.

## Run it with the pipeline

1. Clone the repo to your GitHub account.
2. Add 3 repository secrets (Settings > Secrets and variables > Actions): `APP_KEY`, `MYSQL_PASSWORD`, `MYSQL_ROOT_PASSWORD`.
3. On the machine that will host the app, install a self-hosted runner (Settings > Actions > Runners > New self-hosted runner, GitHub shows the commands). The machine needs Docker and Docker Compose, and the runner user must be in the `docker` group. I used Docker 29.8.1.
4. Publish a GitHub release with a new tag (for example `v1.5`). This starts the pipeline and the deploy.
5. When the run is green, open http://localhost:8080 on that machine.

## Architecture

```
browser --8080--> nginx --9000--> app (PHP-FPM, Laravel) --3306--> mysql (volume)
```

- nginx (`nginx:alpine`) is the only service with a published port. It serves static files and sends PHP requests to the app.
- app is built from the `Dockerfile` (`php:8.3-fpm-alpine`) and runs as the non-root user `www-data`. At start it runs the migrations and the seeder, then PHP-FPM.
- mysql (`mysql:8.0`) keeps its data in the volume `mysql-data`.
- The app and mysql are on an internal Docker network, so they cannot be reached from outside.
- mysql and nginx have healthchecks. The app starts after mysql is healthy, and the nginx healthcheck calls `/api/ready`.

## Pipeline

File: `.github/workflows/main.yml`. It runs on push and pull request to `main`, and when a release is published.

```
tests-and-lint
                   Deploy & Post-Verify (self-hosted runner, release only)
security-scan 
```

- `tests-and-lint`: composer install (cached), lint (Pint), tests (7 tests), composer audit.
- `security-scan`: builds the image, tagged with the short commit SHA, and scans it with Trivy.
- `Deploy & Post-Verify`: runs only on a published release and only if the two jobs above passed. It writes `.env` from the GitHub secrets, builds the image, starts the stack with `docker compose up --wait`, then checks `/api/health` and `/api/ready`. If the check fails, it runs `scripts/rollback.sh HEAD~1`.

Automated checks: lint, tests, composer audit, Trivy scan, container healthchecks, post-deploy health and ready check.

Intentional failure: I changed the route `/health` to `/heal`. The tests failed and the deploy did not run (EVIDENCE.pdf, figures 3 and 4).

Rollback test: I published `v1.3` as a false release. The deploy failed and the application stayed available (figures 6 to 8).

## Secrets and security

- Secrets are in GitHub Secrets (pipeline) or a local `.env` (not committed, not copied into the image). The repo only has `.env.example`.
- The deploy job writes `.env` with `umask 077`.
- The app runs as `www-data`, not root.
- The app connects to MySQL with its own user. The root password is only given to the mysql container.
- `APP_DEBUG=false`.
- Trivy and composer audit run on every pipeline run.
- The deploy runs only on releases, so pull request code never runs on my runner.

## Observability

- `/api/health`: the app is alive.
- `/api/ready`: the app can reach the database. It returns 503 when the database is down.
- Logs go to stderr as JSON. Read them with `docker compose logs app`.
- Metrics and alerts (proposal, not built): check `/api/ready` and alert after 3 failures in a row; alert when 5xx responses are above 5% for 5 minutes; alert when a container restarts more than twice in an hour; alert when disk usage is above 80%. Tools: Prometheus, Grafana, Alertmanager.

## Decisions

- Docker Compose: the task asks for a local setup, and one file starts everything.
- Alpine images for the app and nginx: smaller and fewer packages. MySQL uses the official image.
- Non-root user: limits the damage if the PHP process is attacked.
- GitHub Actions: it is in the repo and does everything I need.
- Self-hosted runner on the same machine: a GitHub-hosted runner cannot reach my machine without a public IP or SSH. It is only used for releases.
- Deploy on release: a release is a clear manual decision and gives a version name.
- Image tag = commit SHA, so each version matches a commit.

## Cloud path (low cost)

1. One small server (a VPS, or one EC2 instance with an Elastic IP) running this same compose file, with a domain and HTTPS. Reserved pricing on AWS is much cheaper than on-demand for a steady workload.
2. If it grows, move the database to a managed one (RDS).
3. Only if it gets big and needs zero-downtime updates, move to Kubernetes (EKS) with Helm.

## Limitations

- The rollback resets the code to the previous commit and rebuilds. It does not undo database migrations, so take a backup before risky releases.
- The nginx main process runs as root (stock image). The app container is non-root.
- One server only. No metrics stack.

## AI-use disclosure

I used AI assistants as helpers, not to do the whole task. I ran and tested everything myself.

- Reviewed my Dockerfile and compose file (Alpine, layer caching, non-root, healthchecks, start order).
- Reviewed my GitHub Actions workflow for best practices and secret leaks.
- Helped me fix errors in the bash rollback script, lint failures, Trivy findings, and test failures.
- Helped write the health endpoints and their tests.
- Helped me make README, and the runbook seem more formal and comprehensive .

The Laravel library application was built earlier with AI help. It is outside this task and I only use it as the sample service.
