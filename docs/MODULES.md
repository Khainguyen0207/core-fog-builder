# Modules

## Inventory

| Package | Namespace | Responsibility | Table keys |
|---|---|---|---|
| `figure-admin/shared` | `Modules\Shared` | Shared admin core, layouts, builders, registries, endpoints, runtime | None |
| `figure-admin/auth` | `Modules\Auth` | Admin login and logout | None |
| `figure-admin/dashboard` | `Modules\Dashboard` | Admin analytics dashboard | None |
| `figure-admin/payments` | `Modules\Payments` | Transactions | `transactions` |
| `figure-admin/customers` | `Modules\Customers` | Customers and membership settings | `customers`, `membership-settings` |
| `figure-admin/users` | `Modules\Users` | Administrative users | `users` |
| `figure-admin/booking` | `Modules\Booking` | Bookings, booking services, calendar, invoice export | `bookings`, `booking-services` |
| `figure-admin/catalog` | `Modules\Catalog` | Services and categories | `services`, `categories` |
| `figure-admin/promotions` | `Modules\Promotions` | Coupons, applicability, and redemption history | `coupons`, `coupon-applicables`, `coupon-redemptions` |
| `figure-admin/workforce` | `Modules\Workforce` | Staff, reviews, and active-staff settings | `staffs`, `staff-reviews` |
| `figure-admin/cms` | `Modules\Cms` | Posts, blog categories, tags, and comments | `posts`, `blog_categories`, `tags`, `comments` |
| `figure-admin/communications` | `Modules\Communications` | Email templates, bulk email, and Telegram callback | `email_templates`, `send_email_users` |
| `figure-admin/settings` | `Modules\Settings` | System, work-time, SePay, information, and Telegram settings | `base table` |

## Standard Module Layout

```text
modules/<Module>/
├── composer.json
├── routes/admin.php
├── src/
│   ├── Providers/<Module>ServiceProvider.php
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

A feature provider must not:

- Scan directories for classes.
- Write generated cache files during boot.
- depend on provider boot order for required bindings.
- silently replace an existing registry key.

## Route Rules

Admin feature routes normally use:

```php
['web', 'auth', 'ip.manager']
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
$tables->register('users', UserTable::class);
```

The provider key, `Table::setName()`, and `{table}` value sent to
`admin.get-data` must match exactly.

Registration is idempotent only when the key and class are identical. A key
collision with a different class must throw.

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
work. Domain interactions should remain in the host application or move behind a
planned contract.
