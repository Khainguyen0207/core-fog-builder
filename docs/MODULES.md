# Modules

## Inventory

| Package | State | Dependencies | Responsibility | Table keys |
|---|---|---|---|---|
| `figure-admin/shared` | Core | None | Shared layouts, builders, registries, endpoints, runtime | None |
| `figure-admin/auth` | Core | Users, Shared | Admin and customer authentication | None |
| `figure-admin/dashboard` | Core | Shared | Mock-backed core analytics dashboard | None |
| `figure-admin/customers` | Core | Users, Shared | Customers and membership settings | `customers`, `membership-settings` |
| `figure-admin/users` | Core | Shared | Administrative users | `users` |
| `figure-admin/settings` | Core | Shared | Core system, work-time, information, and plugin settings | `base table` |
| `figure-admin/catalog` | Optional | None optional | Services and categories | `services`, `categories` |
| `figure-admin/workforce` | Optional | Catalog | Staff, reviews, and active-staff settings | `staffs`, `staff-reviews` |
| `figure-admin/booking` | Optional | Catalog, Workforce | Bookings, services, calendar, invoice export | `bookings`, `booking-services` |
| `figure-admin/payments` | Optional | Booking | Transactions and SePay integration | `transactions` |
| `figure-admin/promotions` | Optional | Booking | Coupons, applicability, and redemptions | `coupons`, `coupon-applicables`, `coupon-redemptions` |
| `figure-admin/communications` | Optional | Booking | Email templates, reminders, bulk email, and Telegram | `email_templates`, `send_email_users` |
| `figure-admin/cms` | Optional | None optional | Posts, categories, tags, comments, and views | `posts`, `blog_categories`, `tags`, `comments` |

Core packages cannot be disabled. Optional packages are identified by the exact
Composer names above, default to disabled, and are enabled from Settings >
Plugins. Enabling requires the package and all dependencies to be installed and
selected. Disabling retains migrations and business data.

## Standard Module Layout

```text
modules/<Module>/
├── composer.json
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── routes/admin.php
├── src/
│   ├── Providers/<Module>ServiceProvider.php
│   ├── Domain/
│   ├── Http/Admin/Controllers/
│   ├── Http/Requests/
│   └── Admin/
│       ├── Forms/
│       ├── Tables/
│       └── Panels/
└── resources/
    ├── views/
    ├── js/
    └── scss/
```

Not every module needs every directory. Keep feature-specific code inside its
owner instead of adding empty abstractions.

## Provider Responsibilities

A feature provider may:

- Load module routes with `loadRoutesFrom()`.
- Load namespaced views with `loadViewsFrom()`.
- Explicitly register table keys in `TableRegistry`.
- Register stable, ordered menu entries in `MenuRegistry`.
- Register explicitly authorized bulk-delete handlers when the feature supports
  them.
- Load package-owned migrations.
- Register package-owned schedules and listeners with execution-time gates.

A feature provider must not:

- Scan directories for classes.
- Write generated cache files during boot.
- depend on provider boot order for required bindings.
- silently replace an existing registry key.

## Route Rules

Optional admin routes normally use:

```php
['web', 'auth', 'ip.manager', 'plugin:figure-admin/<package>']
```

Route names use the `admin.*` namespace. Use explicit controller class references.

Intentional exceptions:

- Login routes use `web,guest`.
- Logout uses `web,auth,ip.manager`.
- Communications exposes the existing unnamed guest route
  `GET /updated-activity`.

Preserve static-before-wildcard ordering inside module route files.

## Table Registration Rules

Every remotely queried table must be registered explicitly:

```php
$tables->register('services', ServiceTable::class, 'figure-admin/catalog');
```

The provider key, `Table::setName()`, and `{table}` value sent to
`admin.get-data` must match exactly.

Registration is idempotent only when the key and class are identical. A key
collision with a different class or owner must throw. Optional menu, table, and
bulk-handler registrations must include their exact Composer owner. Registries
retain all registrations and filter effective reads dynamically; disabling a
plugin does not require rebooting or rebuilding routes.

Do not normalize historical keys without a compatibility review. Current keys
intentionally include hyphens, underscores, and the legacy `base table` key.

## Menu Registration Rules

Use stable unique keys and numeric ordering. Current top-level order is:

| Order | Entry |
|---:|---|
| 100 | Dashboard |
| 200 | Payments |
| 300 | Customers |
| 400 | Users |
| 500 | Booking |
| 600 | Catalog |
| 700 | Promotions |
| 800 | Workforce |
| 900 | CMS |
| 1000 | Communications |
| 1100 | Log Viewer, conditionally supplied by Shared |
| 1200 | Settings |
| 1300 | Logout |

Identical duplicate menu registration is allowed. Conflicting registration under
the same key is an error.

## Cross-Module References

Named routes are the current integration contract between several admin modules.
Examples include Users linking to Customers/Workforce and Workforce linking to
Booking. Preserve route names when moving implementation details.

Do not import another module's admin implementation solely to perform domain
work. Declare package dependencies for required domain imports and use core
events/contracts for optional integrations. Core code must not query optional
tables or generate optional routes while the owner is ineffective.
