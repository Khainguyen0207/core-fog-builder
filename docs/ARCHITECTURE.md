# Architecture

## Runtime Shape

The repository is one Laravel application assembled from a host and local
Composer path packages.

```text
Host application
├── app/                  Domain/application code and APIs
├── database/             Shared schema, factories, and seeders
├── routes/api.php        Customer, staff, and public APIs
├── routes/web.php        Root/admin redirects and host integrations
├── config/               Host configuration and Shared overrides
└── modules/
    ├── Shared/           Reusable admin core
    └── <Feature>/        Admin vertical slices
```

## Composer Packages

The root Composer repository maps `modules/*` as symlinked path packages. Local
package versions are exposed as `dev-main`.

The host provides a virtual compatibility dependency:

```json
"provide": {
    "figure-admin/host": "1.0.0"
}
```

Feature packages require `figure-admin/host` because they still import domain
classes from `App\...`. They are application-integrated packages, not standalone
domain libraries.

`figure-admin/shared` is different. It must remain host-independent and may only
depend on PHP, Laravel, Yajra DataTables, and other explicitly declared generic
libraries.

## Dependency Direction

Allowed:

```text
Host -> Shared + feature packages
Feature package -> Shared
Feature package -> host domain through figure-admin/host
```

Forbidden:

```text
Shared -> App\...
Shared -> feature package
Shared -> host routes, models, enums, middleware aliases, or global views
```

Host-specific behavior is supplied through configuration or package contracts.
For example, the host adds `ip.manager` to Shared routes through
`config/figure-admin-shared.php`; the Shared package default does not know that
middleware alias.

## Ownership Rules

The host owns:

- Eloquent models and domain enums under `app/Models` and `app/Enums`.
- Actions, services, jobs, events, listeners, API controllers, and resources.
- Middleware and middleware aliases.
- Database migrations, factories, and seeders.
- Final package composition, environment configuration, and branding overrides.

Feature packages own:

- Admin controllers and Form Requests.
- Concrete Forms, Tables, Panels, and feature policies/actions.
- Admin routes and namespaced feature views.
- Menu entries and explicit table registrations.
- Feature-only JavaScript, SCSS, templates, and assets.

Shared owns:

- Form, field, Table, column, operation, Panel, and registry APIs.
- Shared admin layouts, components, navbar, menu, toasts, and theme.
- DataTable and safe bulk-action endpoints.
- Generic browser runtime and component initialization.
- Host-neutral configuration defaults.

## Provider Loading

All module providers are declared in each module's `composer.json` under
`extra.laravel.providers`. Do not manually register module providers in
`bootstrap/providers.php`.

`bootstrap/providers.php` is reserved for host providers and deliberately manual
third-party providers.

## Removed Legacy Architecture

The following must not be recreated:

- `app/Forms`
- `app/Table`
- `app/Panel`
- `modules/AdminUi`
- `App\Providers\TableServiceProvider`
- Generic host `BulkDeleteController`
- `bootstrap/cache/tables.php`
- `bootstrap/cache/tables.meta.php`
- Runtime table filesystem scanning or cache-file generation

Architecture tests enforce these boundaries.

## Compatibility Contracts

Unless a change explicitly includes a migration plan, preserve:

- Existing `admin.*` route names and URIs.
- Existing table registry keys, including historical hyphen/underscore formats.
- Existing API response envelopes.
- Existing package view namespaces.
- Host middleware requirements on admin routes.
- Persisted model class identities and enum values.
