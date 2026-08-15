# Figure Admin Shared

`figure-admin/shared` is the reusable UI and builder core for Figure Admin feature packages. Consumers require it through Composer:

```json
{
    "require": {
        "figure-admin/shared": "dev-main"
    }
}
```

Laravel package discovery registers `Modules\Shared\Providers\SharedServiceProvider`.

## Public PHP APIs

- Forms extend `Modules\Shared\Forms\Form` and compose fields from `Modules\Shared\Forms\Fields`.
- Tables extend `Modules\Shared\Tables\Table` and compose columns, operations, and header actions from the corresponding `Modules\Shared\Tables` namespaces.
- Panels use `Modules\Shared\Panels\Panel` and `Modules\Shared\Panels\PanelSection`.
- Feature service providers must explicitly register each table key and class with `Modules\Shared\Tables\Registry\TableRegistry`; Shared does not scan application directories.
- Feature service providers contribute navigation through `Modules\Shared\Menu\MenuRegistry`.
- Bulk deletion is opt-in through `Table::hasBulkDelete()`. Every registered table implements `Modules\Shared\BulkActions\Contracts\BulkDeleteHandler` with authenticated-user authorization and model-key deletion by default; override `authorize()` or `delete()` on the table for resource-specific behavior. Unknown, disabled, and model-less tables cannot use the endpoint.

## Routes And Configuration

`config/figure-admin-shared.php` controls route enablement, prefix, name prefix, middleware, branding, layout, assets, logout, and user presentation. The default routes are `admin.get-data` and `admin.bulk-delete`; applications can disable package routes or change their prefix and middleware through configuration.

Views are namespaced under `shared::` and are owned by `resources/views` in this package.

## Frontend Contract

Vite must compile these source entries:

```text
modules/Shared/resources/js/app.js
modules/Shared/resources/scss/admin.scss
```

The JavaScript package declares its browser libraries as peer dependencies in `package.json`; the consuming application supplies compatible versions. Shared owns its layouts, runtime hooks, styles, and theme assets.

Shared must not require `figure-admin/host`, reference `App\...` classes, or depend on host-owned views and assets. Host and feature packages integrate only through the public registries, builders, configuration, routes, and namespaced views above.
