# Agent Handoff

This checklist is mandatory for future coding sessions and agent handoffs.

## Start of Session

1. Read `AGENTS.md`.
2. Read `docs/README.md` and task-relevant files in `docs/*`.
3. Inspect `git status`, current branch, and recent commits.
4. Do not revert, overwrite, or stage unrelated worktree changes.
5. Inspect the owning module and nearby tests before proposing code.

## Architecture Guardrails

1. Shared must not import `App\...` or require `figure-admin/host`.
2. Feature modules own admin vertical slices and depend directly on Shared.
3. Feature providers are Composer-discovered, never manually bootstrapped.
4. Register tables, menus, and bulk handlers explicitly.
5. Do not restore table scanning, generated registry caches, or legacy builders.
6. Use module-owned views and `shared::` layouts/components.
7. Preserve route names, table keys, middleware, and API envelopes unless the task
   explicitly changes the contract.
8. Keep feature-only assets out of Shared.

## DataTable Guardrails

1. Preserve configured query constraints when passing builders to Shared.
2. Qualify duplicated columns in joined queries.
3. Select the base table explicitly when relation sorting can join another table.
4. Treat filter value `0` as valid.
5. Keep raw HTML explicit and escape model-derived content.
6. Keep row authorization and visibility in the concrete feature table.
7. Add endpoint tests using real DataTables `columns` and `order` payloads.

## UI Guardrails

1. Scope JavaScript behavior with `data-shared-*` component roots.
2. Do not add executable inline JavaScript to Shared views.
3. Support multiple tables/forms/editors on one page.
4. Keep mobile document width contained; tables scroll inside their card.
5. Add new Vite entries explicitly and verify the production manifest.
6. Preserve required compatibility globals until consumers are migrated.

## Verification Checklist

Run the narrowest affected tests first, then as applicable:

```bash
php artisan test tests/Feature/Modules/<Module>/<Module>AdminModuleTest.php
php artisan test tests/Feature/Modules/Shared/SharedCoreTest.php
php artisan test tests/Feature/SharedCoreArchitectureTest.php
php artisan test
./vendor/bin/pint --test
composer validate --strict
php artisan package:discover --ansi
php artisan route:list --path=admin -vv
php artisan view:cache
npm run build
npx playwright test playwright-calendar.spec.js
git diff --check
```

Do not claim a check passed unless it ran successfully in the current worktree.

## End of Session

1. Re-read the diff for accidental scope expansion.
2. Remove generated test artifacts that are not intended source files.
3. Update docs when contracts or workflows changed.
4. Report tests, build status, warnings, and residual risks.
5. Commit, push, or create a PR only when explicitly requested.
