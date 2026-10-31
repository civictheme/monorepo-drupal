# Post-provision hooks

Drop-in scripts that run at the tail of `scripts/quant/deploy.sh` — step 3 of
the four-step model in [`docs/deployment.md`](../../../docs/deployment.md).

## They run on every deploy

Locally they run after `ahoy build`, `ahoy provision` and `ahoy deploy`. On
Salsa Hosting they run after **every** deploy of development and production.

**Write them idempotent.** A hook that assumes a fresh site — creating a term, a
user, a menu link — will do it again on every deploy. Look before you create:

```sh
# Wrong: a new term on every deploy.
"$DRUSH" php:eval '\Drupal\taxonomy\Entity\Term::create([
  "vid" => "tags", "name" => "Featured",
])->save();'

# Right: create only when it is missing.
"$DRUSH" php:eval '
  $storage = \Drupal::entityTypeManager()->getStorage("taxonomy_term");
  if (!$storage->loadByProperties(["vid" => "tags", "name" => "Featured"])) {
    $storage->create(["vid" => "tags", "name" => "Featured"])->save();
  }'
```

Better still, use `drush deploy:hook` for work that should run exactly once —
Drupal records which hooks have already run, so you do not have to.

## Conventions

- One file per hook, named `NN-<purpose>.sh` (numeric prefix controls order).
  Same convention as `.docker/quant/entrypoints/`.
- Files ending in `.disabled` are skipped. To enable a stub: rename
  `10-foo.sh.disabled` → `10-foo.sh` **and** `chmod +x 10-foo.sh`.
- Non-executable `*.sh` files are skipped with a notice, so half-written drafts
  do not abort provisioning.

## Execution context

Hooks run **inside the app container**, invoked directly by the runner
(`"$hook"`) — they execute under their own shebang, so the runner does **not**
impose `bash -e` or any other strict-mode flags on you. If you want fail-fast
behaviour inside a hook, set it explicitly:

```sh
#!/usr/bin/env bash
set -euo pipefail
```

The runner exports the following before invoking each hook, so you do not need
to re-derive them:

| Variable | Typical value |
| --- | --- |
| `DRUSH` | `/opt/drupal/bin/drush` |
| `APP_ROOT` | `/opt/drupal` |
| `DRUPAL_INSTALL_PROFILE` | Profile machine name or recipe path |
| `SCAFFOLD_PROVISION_TYPE` | `profile` or `database` |
| `QUANT_ENV_TYPE` | `local`, `development`, `production` |

Use `QUANT_ENV_TYPE` to gate behaviour per environment — there is intentionally
no `prod/`, `dev/` subdirectory split (a single flat `post-provision.d/`
keeps the surface small).

## Failure semantics

Default is **abort** — a non-zero exit from any hook fails the whole
provision. Set `SCAFFOLD_POST_PROVISION_ON_ERROR=continue` to soften (the runner
will report failures at the end but exit 0).

To skip the post-provision phase entirely:
`SCAFFOLD_POST_PROVISION_ENABLED=0 ahoy post-provision`.

## Running standalone

```sh
ahoy post-provision
```

Re-runs every enabled hook against the existing site without reinstalling /
re-importing. Useful for iterating on a hook script.

## Provided stubs

| File | What enabling it does |
| --- | --- |
| `10-search-reindex.sh.disabled` | Resets every Search API tracker and reindexes. Cheap on fresh installs, expensive on populated databases. |
| `20-content-recipe.sh.disabled` | Applies an additional `drush recipe` (commented placeholder — point at the recipe you want imported on every provision). |
| `30-cache-rebuild.sh.disabled` | Final `drush cr`. Worth keeping after recipe imports, which sometimes leave stale caches. |

Drop the `.disabled` suffix and `chmod +x` to enable.
