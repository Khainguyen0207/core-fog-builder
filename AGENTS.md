# AGENTS.md
## Purpose
- Operating guide for agentic coding assistants in this repository.
- Captures verified commands plus practical coding conventions from existing code.
- Stack: Laravel 12, PHP 8.2+, MySQL, Vite 6, Blade + Bootstrap/jQuery.

## Required Project Docs
- Read `docs/README.md` and task-relevant files under `docs/*` before changing code.
- Treat `docs/ARCHITECTURE.md`, `docs/SHARED-CORE.md`, and
  `docs/AGENT-HANDOFF.md` as repository rules unless they conflict with direct or
  higher-priority instructions.
- Update the relevant document whenever architecture, module ownership, public
  Shared APIs, registration contracts, or verification workflows change.
## Repository Map
- Backend code: `app/`
- API routes: `routes/api.php`
- Admin/web routes: `routes/web.php`
- Tests: `tests/Feature`, `tests/Unit`
- DB files: `database/migrations`, `database/seeders`, `database/factories`
- Frontend entrypoints: `vite.config.js`
- PHP scripts/deps: `composer.json`
- JS scripts/deps: `package.json`
## Cursor/Copilot Rule Files
- `.cursorrules`: not found
- `.cursor/rules/`: not found
- `.github/copilot-instructions.md`: not found
- If added later, treat them as higher-priority local rules.
## Setup and Run
### One-command bootstrap
```bash
composer setup
```
### Local setup
```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
npm install
```
### Local dev (recommended)
```bash
composer dev
```
### Local dev (manual, separate terminals)
```bash
php artisan serve
php artisan queue:work
php artisan schedule:work
npm run dev
```
### Docker dev
```bash
docker compose -f docker-compose.dev.yaml up --build -d
```
## Build, Lint, Test
### Build
```bash
npm run build
```
Optional cache warmup:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
### Lint/format (PHP)
- Tool: Laravel Pint (`./vendor/bin/pint`)
- No custom Pint config file detected (default Laravel preset behavior)
```bash
./vendor/bin/pint --test
./vendor/bin/pint
./vendor/bin/pint app/Http/Controllers/API/BookingController.php
```
### Tests
```bash
composer test
php artisan test
php artisan test --testsuite=Feature
php artisan test --testsuite=Unit
php artisan test tests/Feature/ExampleTest.php
php artisan test tests/Feature/ExampleTest.php --filter=test_the_application_returns_a_successful_response
php artisan test --parallel
```
- Test environment defaults come from `phpunit.xml` and use in-memory SQLite (`DB_DATABASE=:memory:`).
### Single test in Docker
```bash
docker compose -f docker-compose.dev.yaml exec moto-service-manager-be php artisan test tests/Feature/ExampleTest.php --filter=test_the_application_returns_a_successful_response
```
## Code Style Guidelines
### Formatting
- Follow `.editorconfig`: UTF-8, LF, 4 spaces, final newline, trim trailing spaces.
- YAML indentation is 2 spaces.
- Keep one class per file and PSR-4 namespace/path alignment.
### Imports
- Use explicit `use` imports; avoid wildcard imports.
- One import per line.
- Typical local order: `App\...` imports first, framework/vendor after.
- Remove unused imports when touching files.
### Types
- Prefer parameter and return types on methods.
- Use relation return types where practical.
- Codebase is mixed on `declare(strict_types=1);`; do not mass-introduce it.
- If a file already uses strict types, maintain consistency within that file.
### Naming
- Classes: `PascalCase` (`*Controller`, `*Request`, `*Resource`, `*Service`, `*Action`).
- Methods/variables: `camelCase`.
- DB columns and JSON keys: `snake_case`.
- Route names: dotted names under route groups (example: `admin.services.index`).
- Enum constants (`app/Enums/*Enum.php`): `UPPER_SNAKE_CASE`.
### Validation
- Prefer Form Requests in `app/Http/Requests/Admin` and `app/Http/Requests/API`.
- Use `Rule::in(SomeEnum::cases())` for enum-like fields.
- Put custom messages in `messages()` when needed.
### Controllers
- Keep controllers thin; move business logic to `Actions`/`Services`.
- Prefer dependency injection over service location in controllers.
- Use `firstOrFail`/`findOrFail` for resource lookups.
- Admin controllers generally redirect with flash success messages.
### API Response Contract
- Keep the existing JSON envelope for API endpoints:
  - `error` (bool)
  - `data` (payload, null, or empty array)
  - `message` (string)
- Use correct status codes (`422`, `404`, etc.) for failure cases.
### Models and Eloquent
- Define `$fillable` explicitly.
- Define `$casts` explicitly (datetime, decimal, integer, enum cast classes).
- Use eager loading (`with`, `loadMissing`) on resource-heavy endpoints.
- Keep relationship names clear and conventional.
### Actions and Services
- Put multi-step domain logic in `app/Actions` and `app/Services`.
- Wrap multi-write flows in DB transactions.
- Roll back on failure and rethrow unless intentional boundary translation occurs.
- Trigger side effects (jobs/notifications) on successful commit paths.
### Error Handling
- Catch broad `Throwable` mainly at HTTP boundaries.
- Do not silently swallow exceptions.
- Keep API error payload shape consistent with the standard envelope.
### Frontend/Assets
- Register new JS/SCSS entrypoints in `vite.config.js`.
- Existing admin assets rely on globals like `window.$` and `window.Swal`; keep compatibility.
- No dedicated JS lint/test scripts are currently configured.
### Tests
- `tests/Feature` for HTTP/integration behavior.
- `tests/Unit` for isolated logic.
- Prefer behavior assertions (response, DB effects, jobs/events) over internals.
- Keep tests deterministic; avoid unstable time-dependent assertions.
## Agent Workflow Expectations
- Read nearby code before edits and match local conventions.
- Keep diffs focused; avoid unrelated refactors.
- Run Pint on changed PHP files.
- Run narrow tests first, then broader tests if needed.
- If verification is not possible, state what could not be verified.
## Quick Do/Do Not
- Do preserve API response contract and route naming patterns.
- Do use Form Requests and Resources for new API endpoints.
- Do keep business logic out of routes and mostly out of controllers.
- Do not introduce new global helper functions unless clearly cross-cutting.
- Do not change enum status/value strings without compatibility review.
- Do not commit secrets from `.env` or credential files.
