# Shared Core

## Package Contract

Package: `figure-admin/shared`

Namespace: `Modules\Shared\`

View namespace: `shared::`

Shared is reusable admin infrastructure. It must not require
`figure-admin/host`, import `App\...`, or assume feature routes and domain models
exist.

## Forms

Base class:

```php
Modules\Shared\Forms\Form
```

Fields:

- `InputField`
- `SelectField`
- `SwitchField`
- `EditorField`

Prefer explicit form configuration when route inference is not guaranteed:

```php
return $this
    ->model(ModelClass::class)
    ->action(route('admin.resource.store'))
    ->method('POST')
    ->cancelUrl(route('admin.resource.index'));
```

The default templates are `shared::forms.page` and `shared::forms.form`.

Rules:

- Field names and DOM IDs must be unique within the page.
- Render required, disabled, and readonly state consistently.
- Enable multipart encoding only when the form contains files.
- Normalize scalar values, backed enums, and project enum-like values before
  rendering selections.
- Do not infer domain behavior in Shared field components.

## Tables

Base class:

```php
Modules\Shared\Tables\Table
```

The table API includes columns, format columns, operations, header actions,
filters, `TableRegistry`, and `TableFactory`.

### Query Rules

Configured builders are cloned with their constraints and eager loads intact.
Never replace a configured builder with `newQuery()` during execution.

Qualify base-table columns whenever relation sorting/filtering can introduce a
join:

```php
User::query()
    ->select('users.*')
    ->whereNot('users.id', Auth::id());
```

This is mandatory for joined tables because:

- `where id = ...` becomes ambiguous when both tables contain `id`.
- `select *` allows joined `id` columns to overwrite the base model key during
  hydration.

Use relation notation such as `customer.name` only when the relation and query
behavior are covered by an endpoint test that exercises filtering or sorting.

### Output Rules

- Raw HTML columns must be declared explicitly.
- Escape model-derived content before composing raw HTML.
- Operations are rendered server-side with the final row key and URL.
- Do not restore placeholder `/0` URL replacement in JavaScript.
- Table-specific authorization and row visibility belong in the concrete table,
  not the Shared base class.
- The Users table owns current-user exclusion explicitly.

### Filter Rules

- Accept malformed or absent DataTables search JSON without throwing.
- Treat `0` as a valid filter value.
- Apply range filters once per column.
- Qualify filter columns when joins make names ambiguous.

## Panels

Shared exposes:

```php
Modules\Shared\Panels\Panel
Modules\Shared\Panels\PanelSection
```

Default views are `shared::panels.card` and `shared::panels.page`. State must be
initialized before rendering, and missing templates must fail clearly.

## Registries

Shared provides explicit registries for:

- Tables
- Menus
- Bulk-delete handlers

All registries detect conflicting keys. Do not silently overwrite registrations.

## Bulk Deletion

Bulk deletion is disabled by default. A feature must register a class implementing
`BulkDeleteHandler` and must authorize the requested IDs itself.

Shared runs approved deletion inside a transaction and returns safe JSON error
envelopes. Do not add a generic model mass-delete fallback.

## Shared Routes

Default routes:

| Method | URI | Name |
|---|---|---|
| POST | `/admin/get-data/{table}` | `admin.get-data` |
| POST | `/admin/bulk-delete` | `admin.bulk-delete` |

Prefix, name prefix, and middleware are configurable in
`figure-admin-shared.php`. Package defaults are host-neutral. The root override
adds `ip.manager`.

## Views and Frontend Runtime

Shared owns the active admin layout, navbar, menu, footer, toasts, forms, fields,
tables, operations, and panels.

Use package-qualified views:

```blade
@extends('shared::layouts.content')
@include('shared::tables.table')
```

Do not recreate global `admin.layouts.*` or `admin.components.*` dependencies.

Component runtime hooks use `data-shared-*`, including:

- `data-shared-form`
- `data-shared-table`
- `data-shared-editor`
- `data-shared-file-preview`
- `data-shared-menu-search`

Executable inline scripts are forbidden in Shared views. JSON configuration
blocks and configured external script references are allowed.

Initialize dynamic content through:

```js
window.FigureAdminShared.initialize(scope);
```

Shared intentionally exposes compatibility globals used by current features:

- `window.$` and `window.jQuery`
- `window.bootstrap`
- `window.flatpickr`
- `window.moment`
- `window.PerfectScrollbar`
- `window.Quill`
- `window.Swal`

Keep browser queries scoped to component roots. Avoid singleton IDs so multiple
forms, editors, or tables can coexist.

## Styling Rules

- Put generic component styles in Shared SCSS.
- Keep Booking calendar, Dashboard charts, Communications previews, and other
  feature styles in their owner modules.
- Shared DataTables must contain horizontal overflow inside the table card on
  mobile.
- DataTable header sorting uses two staggered opposite `➜` glyphs and must not
  show an outline/box-shadow on hover.
- Preserve Bootstrap 5 compatibility for `bootstrap-select`.
