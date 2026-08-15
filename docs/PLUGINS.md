# Plugin Operations

## Two Separate States

An optional feature must be both installed and enabled:

1. Composer installation makes its provider and code available.
2. The `settings.enabled_plugins` JSON array requests runtime enablement.

Installing a package does not enable it. Selecting an uninstalled package is
rejected. Core packages are always effective and cannot be selected or disabled.

## Catalog

`config/figure-admin-plugins.php` is the explicit source of truth. Plugin IDs are
exact Composer package names; directory names and display labels are not IDs.

| Package | Type | Optional dependencies |
|---|---|---|
| `figure-admin/shared` | Core | None |
| `figure-admin/users` | Core | None |
| `figure-admin/auth` | Core | None |
| `figure-admin/customers` | Core | None |
| `figure-admin/dashboard` | Core | None |
| `figure-admin/settings` | Core | None |
| `figure-admin/catalog` | Optional | None |
| `figure-admin/workforce` | Optional | Catalog |
| `figure-admin/booking` | Optional | Catalog, Workforce |
| `figure-admin/payments` | Optional | Booking |
| `figure-admin/promotions` | Optional | Booking |
| `figure-admin/communications` | Optional | Booking |
| `figure-admin/cms` | Optional | None |

The catalog owns dependency rules. Do not duplicate dependency checks in module
providers, controllers, jobs, or views.

## Enabling Plugins

1. Install the package and every dependency with Composer.
2. Run migrations so installed package schemas are current.
3. Sign in as an administrator.
4. Open **Settings > Plugins**.
5. Select dependencies and dependents together, then save.

The manager rejects unknown packages, core package names, missing Composer
packages, and incomplete dependency chains. Stored package names are normalized
to catalog order.

Fresh and seeded systems use `[]`, so every optional plugin starts disabled.
After enabling plugins, `php artisan db:seed` can run their package seeders in
dependency order. The Settings seeder preserves the current plugin selection.

## Disabling Plugins

Disable dependents before their dependencies. For example, disable Payments,
Promotions, and Communications before disabling Booking; disable Booking before
Workforce or Catalog.

Disabling a plugin:

- removes its menu, table, and bulk-handler registrations from effective reads;
- returns 404 for explicitly disabled admin/API routes;
- prevents schedules, stale queued jobs, listeners, callbacks, and internal
  extension paths from performing plugin work;
- hides its Settings contributions;
- retains migrations, tables, and business data.

A requested plugin whose package or dependency is unavailable returns 503 at its
route boundary. API errors retain the `error`, `data`, `message` envelope.

## Route And Cache Behavior

Routes from installed packages remain statically registered. The
`plugin:<composer-package>` middleware evaluates database state at request time,
so changing plugin state does not require rebuilding the route cache.

Menus and Shared registries also evaluate effective state dynamically. Never
conditionally register routes or remove registry entries during provider boot.

Long-running queue and scheduler workers should be restarted after changing
installed packages or deploying code. Execution-time gates still protect work
that was queued before a plugin was disabled.

## Composer Deployment

The monorepo installs optional packages through root `require-dev` for development
and tests. A core-only production install uses:

```bash
composer install --no-dev --optimize-autoloader
```

To deploy plugins in production, move or add the desired packages and complete
dependency chain to root `require` before the production install. The `suggest`
section documents available packages but does not install them.

Vite reads `vendor/composer/installed.json`. Shared and Dashboard assets are
always built; Booking and Communications entries are included only when those
packages are installed. Run Composer before `npm run build` whenever package
composition changes.

## Schema And Data

Core migrations remain under `database/migrations`. Optional migrations,
factories, and seeders live under each owning package's `database` directory.
Installed providers load migrations regardless of enabled state, allowing schema
preparation before activation.

Migration basenames are compatibility contracts because Laravel stores them in
the migration ledger. Normal disablement must never run down migrations. Treat
package uninstall and destructive data removal as separate, explicitly reviewed
operations.

## Adding An Optional Plugin

1. Create its Composer package and discovery provider.
2. Declare exact dependencies in both Composer and the host plugin catalog.
3. Add the package to root `require-dev` and `suggest` for monorepo development.
4. Add `plugin:<package-name>` middleware to every installed route boundary.
5. Pass the package owner to every optional menu, table, and bulk registration.
6. Add execution-time guards to schedules, jobs, listeners, callbacks, and
   internal extension paths.
7. Keep migrations, seeders, domain code, views, and assets in the owner package.
8. Test disabled, enabled, missing-package, dependency-blocked, stale-work, and
   route-cache states.

See `docs/ARCHITECTURE.md`, `docs/MODULES.md`, and `docs/TESTING.md` for the
corresponding engineering contracts.
