# Project Documentation

This directory is the operational source of truth for the Figure Admin codebase.
Read these documents before changing architecture, modules, Shared UI, routes, or
package configuration.

## Reading Order

1. [Architecture](ARCHITECTURE.md)
2. [Plugin Operations](PLUGINS.md)
3. [Modules](MODULES.md)
4. [Shared Core](SHARED-CORE.md)
5. [Development](DEVELOPMENT.md)
6. [Testing](TESTING.md)
7. [Known Limitations](KNOWN-LIMITATIONS.md)
8. [Agent Handoff](AGENT-HANDOFF.md)

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

- The host owns application composition, core API boundaries, middleware, and the
  centralized plugin catalog/state resolver.
- Core packages are always enabled. Optional packages own their domain classes,
  migrations, seeders, schedules, listeners, admin UI, and feature assets; the
  host retains centralized API composition and plugin integration boundaries.
- `figure-admin/shared` owns reusable admin layouts, Form/Table/Panel builders,
  plugin-aware registries, shared routes, and frontend runtime.
- Installed package providers are loaded by Laravel package discovery; optional
  behavior is then gated by the `enabled_plugins` database setting.
- Tables, menus, and bulk-delete handlers use explicit registration. Filesystem
  discovery is forbidden.
