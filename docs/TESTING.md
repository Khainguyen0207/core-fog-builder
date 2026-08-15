# Testing and Verification

## Test Environment

PHPUnit uses in-memory SQLite with array-backed cache, session, and mail plus a
synchronous queue. Tests are integration-oriented and assemble the host with all
local packages.

## Narrow-to-Broad Order

Start with the smallest affected suite:

```bash
php artisan test tests/Feature/Modules/<Module>/<Module>AdminModuleTest.php
php artisan test tests/Feature/Modules/Shared/SharedCoreTest.php
php artisan test tests/Feature/SharedCoreArchitectureTest.php
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
