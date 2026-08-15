# Project Documentation

This directory is the operational source of truth for the Figure Admin codebase.
Read these documents before changing architecture, modules, Shared UI, routes, or
package configuration.

## Reading Order

1. [Architecture](ARCHITECTURE.md)
2. [Modules](MODULES.md)
3. [Shared Core](SHARED-CORE.md)
4. [Development](DEVELOPMENT.md)
5. [Testing](TESTING.md)
6. [Known Limitations](KNOWN-LIMITATIONS.md)
7. [Agent Handoff](AGENT-HANDOFF.md)

## Authority

- `AGENTS.md` defines repository-wide engineering and tooling rules.
- `docs/*` defines the current modular architecture and workflows.
- Module code and tests define executable behavior.
- `README.md` and `PROJECT_OVERVIEW.md` provide general project context, but do
  not override this documentation or tested behavior.

When documentation and code disagree, inspect tests and runtime behavior, fix the
documentation in the same change, and call out any unresolved mismatch.

## System Summary

The application is a Laravel 12 host with local Composer packages under
`modules/*`.

- The host owns domain models, migrations, services, jobs, APIs, middleware, and
  application composition.
- Feature packages own their admin controllers, requests, forms, tables, routes,
  views, and menu registration.
- `figure-admin/shared` owns reusable admin layouts, Form/Table/Panel builders,
  registries, shared routes, and frontend runtime.
- Feature providers are loaded by Laravel package discovery.
- Tables, menus, and bulk-delete handlers use explicit registration. Filesystem
  discovery is forbidden.
