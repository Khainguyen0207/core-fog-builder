# Architecture

## Runtime Shape

The repository is one Laravel application assembled from a host and local
Composer path packages.

```text
Host application
├── app/                  Core application code and plugin orchestration
├── database/             Core schema, factories, and seeders
├── routes/api.php        Core and runtime-gated plugin APIs
├── routes/web.php        Root/admin redirects and host integrations
├── config/               Host, Shared, and explicit plugin catalog config
└── modules/
    ├── Shared/           Reusable admin infrastructure
    ├── <Core>/           Always-enabled core verticals
    └── <Plugin>/         Optional business verticals
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

Application-integrated packages may require `figure-admin/host`. Optional
packages own domain code under `src/Domain` while retaining established `App\...`
class identities through package classmaps. This preserves serialized jobs,
events, model references, and other persisted compatibility contracts.

`figure-admin/shared` is different. It must remain host-independent and may only
depend on PHP, Laravel, Yajra DataTables, and other explicitly declared generic
libraries.

## Dependency Direction

Allowed:

```text
Host -> core packages + installed optional packages
Feature package -> Shared
Feature package -> host domain through figure-admin/host
Optional package -> declared optional dependency
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

## Plugin Composition

The explicit catalog in `config/figure-admin-plugins.php` is the source of truth
for package identity, core/optional classification, and dependencies. Composer
package names are immutable plugin IDs.

Core packages are always effective:

- `figure-admin/shared`
- `figure-admin/auth`
- `figure-admin/dashboard`
- `figure-admin/customers`
- `figure-admin/users`
- `figure-admin/settings`

Optional packages default to disabled:

- `figure-admin/catalog`
- `figure-admin/workforce`
- `figure-admin/booking`
- `figure-admin/payments`
- `figure-admin/promotions`
- `figure-admin/communications`
- `figure-admin/cms`

Requested optional state is stored as a JSON array in
`settings.enabled_plugins`. `PluginManager` computes effective state from the
catalog, installed Composer packages, requested state, and transitive dependency
availability. Unknown, missing, disabled, and dependency-blocked packages fail
closed. State changes are validated and canonicalized centrally; packages must
not implement their own dependency or fallback logic.

Installed optional routes remain statically registered so route caching is safe.
The `plugin:<composer-name>` middleware gates requests dynamically: explicitly
disabled features return 404, while requested but unavailable features return
503. API failures preserve the standard `error`, `data`, `message` envelope.
Disabling a plugin never drops its schema or data.

## Ownership Rules

The host owns:

- Core domain/application code and cross-plugin integration contracts.
- Middleware and middleware aliases.
- Core database migrations, factories, and seeders.
- The plugin catalog, state persistence, effective-state resolver, and runtime
  middleware fallback.
- Final Composer composition, environment configuration, and branding overrides.

Feature packages own:

- Their domain models, enums, actions, services, jobs, events, and listeners.
- Their migrations, factories, and seeders.
- Their console behavior and package integration registrations, including
  execution-time plugin gates.
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
- Generic registration visibility contracts; the host supplies the
  `PluginManager` adapter.

## Provider Loading

All installed module providers are declared in each module's `composer.json`
under `extra.laravel.providers`. Do not manually register module providers in
`bootstrap/providers.php`. Optional providers may load routes, views, migrations,
schedules, and listeners, but business execution must still check effective
plugin state where work can outlive an HTTP request.

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
