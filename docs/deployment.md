# Deployment process

This project deploys to Salsa Hosting (Quant Cloud). The
`Build and Push civictheme-monorepo-drupal to Quant Cloud` workflow
(`.github/workflows/build-deploy.yml`) runs on every push to `main`,
`develop`, `release/*`, `hotfix/*`, `project/*`, selected feature branches,
and tags:

1. For `develop`/`main`/tags it first waits for the commit's CI run to pass;
   preview branches deploy immediately.
2. It builds the app image (`.docker/drupal/Dockerfile`) — composer install,
   generated `civictheme_demo` sub-theme and built front-end assets are all
   baked in; servers never run composer or npm.
3. It pushes the image and redeploys the matching environment (creating it
   from `production` if absent). A `develop` push also redeploys the three
   content environments (`content-corporate`, `content-government`,
   `content-highereducation`), which run the develop image with
   `CIVICTHEME_CONTENT_PROFILE` set.

## Fresh install on every deploy

These are demo sites: **every deploy reinstalls the site from scratch** —
production included. There is no content to preserve and no database to seed.

The platform gives a container no way to tell a deploy from an idle-sleep
wake-up — every container start runs the same `/quant-entrypoint.d/` hooks.
The pipeline synthesises that signal:

- The workflow bakes a `BUILD_ID` unique to each run into the image
  (`/opt/drupal/.build-id`).
- At container start, `.docker/deployment-scripts/post-deploy.sh` compares it
  with the id recorded in Drupal state (`scaffold.provisioned_build_id`):
  - **Different or no id / Drupal not installed** — first start of a new
    image, i.e. a deploy: run `scripts/quant/provision.sh` (drop tables,
    `drush site:install` from `DRUPAL_INSTALL_PROFILE` — profile or recipe
    path — then the `post-provision.d/` hooks: enable CivicTheme, activate
    `civictheme_demo`, modules, settings, content). The install runs in the
    background so Apache starts within the platform's startup grace period;
    the site serves errors until the install lands (watch progress in the
    environment logs). The id is recorded on success.
  - **Same id** — idle-sleep wake-up or task restart: run only the
    non-destructive `scripts/quant/deploy.sh` (updb + config:import + cr).

A dashboard redeploy of an unchanged image therefore does NOT reinstall; only
a new image (any workflow run, re-runs included) does.

## Environments

Branch pushes map to environments named after the branch. PR/preview
environments are deleted via `.github/workflows/delete-environment.yml`.
Environment variables are copied from `production` at creation (secrets are
not — set `DRUPAL_HASH_SALT` on production; other deployed environments
derive a salt from the injected database secrets).
