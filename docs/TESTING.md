# Testing and Verification

## Test Environment

PHPUnit uses in-memory SQLite with array-backed cache, session, and mail plus a
synchronous queue. Tests are integration-oriented and assemble the host with all
local development packages. Optional packages are installed in development but
default to disabled because `enabled_plugins` is `[]`.

## Narrow-to-Broad Order

Start with the smallest affected suite:

```bash
php artisan test tests/Feature/Modules/<Module>/<Module>AdminModuleTest.php
php artisan test tests/Feature/Modules/Shared/SharedCoreTest.php
php artisan test tests/Feature/SharedCoreArchitectureTest.php
php artisan test tests/Feature/Plugins
```

Then run broader verification:

```bash
php artisan test --testsuite=Feature
composer test
./vendor/bin/pint --test
npm run build
git diff --check
```

## Composer Verification

For package changes:

```bash
composer validate --strict
composer dump-autoload
php artisan package:discover --ansi
composer install --dry-run --no-scripts
```

Validate changed package manifests individually when necessary:

```bash
composer validate --strict modules/<Module>/composer.json
```

Confirm each package is installed as a symlink under `vendor/figure-admin` and
that its provider appears once in package discovery.

For optional composition changes, also verify the production dependency shape:

```bash
composer install --no-dev --dry-run
```

The result must remove all optional Figure packages without removing the six core
packages. Vite must still build from the installed-package manifest without
referencing absent optional source paths.

## Route and Cache Verification

For route changes:

```bash
php artisan route:list --path=admin -vv
php artisan route:clear
php artisan route:cache
php artisan route:clear
```

For view changes:

```bash
php artisan view:clear
php artisan view:cache
```

For suspected stale runtime state:

```bash
php artisan optimize:clear
```

Restart long-running PHP workers after package/class changes.

Plugin route tests must verify that cached routes respond to DB state changes
without regenerating the route cache. Business execution tests must cover stale
queued jobs and schedules after a plugin is disabled.

## Plugin Verification

At minimum, cover:

- Core login, Dashboard, Customers, Users, Settings, and Shared DataTables with
  every optional plugin disabled.
- No optional table queries from core-only requests.
- Disabled admin/API routes and generic table/bulk endpoints.
- Missing-package and dependency-unavailable diagnostics.
- Dynamic menu/table visibility after state changes without application reboot.
- Booking with pay-later and no coupon/notification extensions.
- Execution-time gates for schedules, jobs, listeners, and model callbacks.
- Fresh migration/seed behavior for core-only and enabled dependency chains.

## DataTable Regression Tests

DataTable endpoint tests should include the browser request structure:

- `draw`, `start`, and `length`
- `columns` with correct `data` and `name`
- `order` with the target column index and direction
- JSON-encoded `search.value.dataSearch`

Relation sorting tests must create the related model and assert the returned base
model ID. This catches both ambiguous SQL columns and joined IDs overwriting the
base model key.

Test `0` filters explicitly because falsey values are valid input.

## Browser Testing

The current Playwright smoke suite is:

```bash
npx playwright test playwright-calendar.spec.js
```

Run Laravel and Vite before browser tests. The current local database credentials
used by the test are defined in the spec and may differ from README seed examples.

Browser coverage currently includes:

- Calendar initialization and responsive list mode
- Shared mobile controls and table overflow
- Bootstrap select interaction
- Quill layout
- Password toggles and mobile menu
- Membership Settings table/create UI
- DataTable header styling and sort indicators

Remove generated `test-results/.last-run.json` when it is not an intended tracked
artifact.

## Build Warnings

The production build currently emits known Sass deprecation and large-chunk
warnings. A warning is not a successful reason to skip the build. Report warnings
separately from failures and avoid broad build refactors during unrelated work.

## Required Completion Report

State:

- What changed
- Which narrow and broad tests passed
- Whether Pint, route/view cache, Composer, and Vite checks ran
- Any command that could not run
- Any remaining known risk
