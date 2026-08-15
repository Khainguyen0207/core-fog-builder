# Figure Admin

Laravel 12 backend API and administration panel for service-business operations.
The application is composed from local Composer packages under `modules/*` and
can run as a small core admin or with optional business plugins.

## Architecture

Always-enabled core packages:

- `figure-admin/shared`
- `figure-admin/auth`
- `figure-admin/dashboard`
- `figure-admin/customers`
- `figure-admin/users`
- `figure-admin/settings`

Optional plugins default to disabled:

| Plugin | Requires |
|---|---|
| `figure-admin/catalog` | None |
| `figure-admin/workforce` | Catalog |
| `figure-admin/booking` | Catalog, Workforce |
| `figure-admin/payments` | Booking |
| `figure-admin/promotions` | Booking |
| `figure-admin/communications` | Booking |
| `figure-admin/cms` | None |

Installed plugins are enabled from **Admin > Settings > Plugins**. Requested
state is stored in `settings.enabled_plugins`; dependencies and installed-package
availability are validated centrally. Disabling a plugin hides and gates its
behavior without deleting its schema or data.

See [Plugin Operations](docs/PLUGINS.md) and
[Architecture](docs/ARCHITECTURE.md) for the complete contracts.

## Requirements

- PHP 8.2 or newer
- Composer
- Node.js 20 or newer
- MySQL 8.0

Docker and Docker Compose can provide the application and database services.

## Local Setup

The one-command bootstrap installs dependencies, creates `.env` when missing,
generates the application key, runs core migrations, and builds frontend assets:

```bash
composer setup
```

For a seeded local database, run:

```bash
php artisan db:seed
php artisan storage:link
```

Manual setup:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
npm run build
```

Configure the MySQL connection in `.env` before running migrations:

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=figure_admin
DB_USERNAME=root
DB_PASSWORD=
```

## Development

Start Laravel, the queue listener, logs, and Vite together:

```bash
composer dev
```

Or run services separately:

```bash
php artisan serve
php artisan queue:work
php artisan schedule:work
npm run dev
```

The admin login is available at `http://127.0.0.1:8000/login`. API routes use
the `/api/v1` prefix.

## Docker

Development stack:

```bash
cp .env.example .env
docker compose -f docker-compose.dev.yaml up --build -d
docker compose -f docker-compose.dev.yaml exec moto-service-manager-be php artisan key:generate
docker compose -f docker-compose.dev.yaml exec moto-service-manager-be php artisan migrate --seed
docker compose -f docker-compose.dev.yaml exec moto-service-manager-be php artisan storage:link
```

Open the development application at `http://localhost:8080`.

Production-oriented stack:

```bash
docker compose -f docker-compose.yaml up --build -d
```

Open the production-oriented stack at `http://localhost:8000`.

## Production Composition

Optional packages are development dependencies in this monorepo. A core-only
production install excludes them:

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
```

To deploy an optional plugin, add that package and its dependency chain to the
root Composer `require` section before installing with `--no-dev`. Install the
code before selecting the plugin in Settings. Composer package installation and
database enablement are separate operations.

## Plugin Configuration

Plugin-specific settings are shown only when their owner is effectively enabled:

- Workforce: active-staff limits
- Payments: SePay configuration
- Communications: Telegram configuration

Never commit API tokens, bot tokens, chat IDs, or other credentials. Configure
them through environment variables or the protected Settings UI as appropriate.

## Verification

```bash
composer test
./vendor/bin/pint --test
composer validate --strict
php artisan package:discover --ansi
php artisan route:cache
php artisan view:cache
npm run build
```

Plugin-focused tests are under `tests/Feature/Plugins`.

## Documentation

- [Documentation Index](docs/README.md)
- [Architecture](docs/ARCHITECTURE.md)
- [Plugin Operations](docs/PLUGINS.md)
- [Module Inventory](docs/MODULES.md)
- [Shared Core](docs/SHARED-CORE.md)
- [Development](docs/DEVELOPMENT.md)
- [Testing](docs/TESTING.md)
- [Known Limitations](docs/KNOWN-LIMITATIONS.md)
