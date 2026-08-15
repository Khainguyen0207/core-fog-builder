# Known Limitations

This file records constraints and defects that should not be silently changed as
part of unrelated work.

## Package Boundaries

- Feature packages are admin-layer packages, not standalone domain packages.
- Domain models, migrations, enums, services, and APIs remain host-owned.
- Cross-module links currently use stable route names instead of formal contracts.
- The Shared package is reusable, but the assembled admin application still
  expects host configuration, authentication, and domain packages.

## Tables and Registries

- Table keys use inconsistent historical styles: hyphens, underscores, and the
  Settings key `base table`.
- Bulk deletion is disabled by default and no production feature currently
  registers a bulk-delete handler.
- Several format columns render raw HTML. Model-derived values must be escaped
  before being inserted into links or badges.
- Relation sorting can make unqualified columns ambiguous and can overwrite the
  base model ID when `select *` is used. Follow the rules in `SHARED-CORE.md`.

## Routes and Authorization

- Some resource declarations expose actions their controllers do not implement.
  These contracts were preserved during modularization and need a dedicated route
  cleanup.
- Admin route access primarily relies on authentication and host middleware;
  comprehensive resource policies are not yet present.
- Communications intentionally exposes the unnamed guest route
  `/updated-activity`.
- `routes/api.php` contains duplicate customer login declarations.
- `/api/get-port` is a diagnostic route outside the main versioned API group and
  executes an operating-system networking command. It requires a security review.

## Domain Behavior

- Changing a User role does not enforce Customer/Staff profile consistency.
- Deleting a User has cross-domain foreign-key and cascade effects.
- Current-user protection historically differs between single and bulk deletion;
  Shared bulk deletion is now opt-in to avoid generic destructive behavior.
- Some model casts/schema types are legacy strings rather than booleans or
  datetimes.

## Frontend

- Shared preserves browser globals for legacy feature compatibility.
- The main admin CSS and some JavaScript bundles exceed Vite's 500 kB advisory
  threshold.
- The inherited theme produces Sass deprecation warnings.
- Laravel Debugbar can overlay screenshots in local development; distinguish it
  from application regressions.
- Playwright currently uses a fixed local URL and database credentials from the
  spec rather than environment-driven configuration.

## Setup and Existing Documentation

- Setup commands in root README, Composer scripts, Docker files, and `AGENTS.md`
  are not fully identical. Prefer verified commands in `AGENTS.md` and this docs
  directory.
- `PROJECT_OVERVIEW.md` is descriptive/marketing material, not an architecture
  contract.
- Seed credentials may differ from a developer's existing local database.

## Change Policy

Fix these limitations only in a scoped change with characterization tests and a
compatibility review. Do not preserve a security defect merely because it is
listed here; instead make the behavior change explicit and document it.
