# CivicTheme recipes

The CivicTheme content model ships as [Drupal recipes](https://www.drupal.org/docs/extending-drupal/drupal-recipes):
content types, paragraph types, media types, vocabularies, text formats,
menus, workflows, roles and their fields, displays and views. The theme itself
ships only its own settings (`civictheme_starter.settings`) and block placements.

## Applying

The theme must be installed first: the entity displays shipped by the recipes
use layout plugins the theme provides.

```bash
drush theme:enable civictheme_starter
drush config:set system.theme default civictheme_starter
drush recipe:apply <path-to-theme>/recipes/civictheme_site
drush recipe:apply <path-to-theme>/recipes/civictheme_search
# Pick up theme block placements whose dependencies the recipes created.
drush php:eval '\Drupal::service("config.installer")->installOptionalConfig();'
```

`civictheme_site` composes every recipe except `civictheme_search`, which is a
deliberate add-on. Each recipe can also be applied on its own; composed
dependencies apply automatically.

## Layout

Each recipe directory holds:

- `recipe.yml` — composition (`recipes:`), modules to install (`install:`),
  module-provided config entities to import (`config: import:`) and config
  actions.
- `composer.json` — the contrib modules that recipe's config needs. The
  monorepo merges these through `wikimedia/composer-merge-plugin`; standalone
  consumers get them when the recipes are packaged individually.
- `config/` — the config entities the recipe creates.

## Conventions

- `config: strict: false` everywhere: recipes create config that does not
  exist and leave pre-existing config alone, matching how this config behaved
  when it shipped as theme install/optional config. Target profiles (standard,
  GovCMS) pre-install some of the same modules with modified config.
- `config: import:` lists the config entities of recipe-installed modules.
  The recipe runner installs modules with the config installer in syncing
  mode, so a module installed by a recipe does not create its own config
  entities — anything needed (view modes, module-shipped views, image styles,
  `filter.format.plain_text`, webform defaults) must be imported explicitly.
  Simple config (`*.settings`) is still created by the module install and is
  deliberately not imported.
- Config files carry no `uuid` or `_core` keys.
- Config files declare no `dependencies: theme:` entries. The layout-builder
  displays use `civictheme_*` layout plugins that every theme generated from
  the kit provides under its own name, so the providing theme cannot be named
  statically. The recipes require the active theme to supply those layouts;
  the install flow enables the theme before applying them. The active site
  recalculates the theme dependency when the recipe saves the display
  entities, so the export tooling (`scripts/update.recipe-config.sh`) strips
  `dependencies.theme` when routing exported config back into the recipes.

## Tooling

- `.devtools/recipe-map.py` — classification and validation: asserts every
  config file sits in the recipe that owns it, the composition graph is
  acyclic and covers all cross-recipe config dependencies, and each recipe's
  module needs are met by its chain. Run it after changing recipe contents;
  `--manifest` refreshes `.devtools/recipe-manifest.json`.
- `scripts/update.recipe-config.sh` (monorepo) — re-exports recipe config from
  the active site per the manifest; `check` mode is wired into
  `scripts/lint.theme-config.sh`.
