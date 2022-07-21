CivicTheme theme development
-----------------------

## Terminology

- CivicTheme theme - CivicTheme Drupal theme, developed in this repository
  and automatically published to [its own repository](https://github.com/salsadigitalauorg/civictheme).
- CivicTheme Library - CivicTheme front-end library, developed in this repository
  and automatically published to [its own repository](https://github.com/salsadigitalauorg/civictheme_library).
- Demo theme, consumer theme, reference theme - a theme that uses CivicTheme
  Drupal theme as a base theme.
- Reference site, development site - an example site (this repository) that uses
  CivicTheme Drupal theme to demonstrate all components.

## Requirements and constraints

- CivicTheme theme MAY be used out of the box without any customisations.
- CivicTheme theme MUST NOT be changed for customisations. If customisations are
  required - a consumer theme MUST be created and used as a sub-theme of the CivicTheme theme.
- CivicTheme theme MUST be fully compatible with GovCMS 9 SaaS:
  - MUST NOT have any modules
  - MUST NOT have any libraries
  - MUST NOT rely on GovCMS content structures
  - MUST assume that FE compilation happens on local machine and then committed
    to repository
- MUST provide a static version of compiled Storybook for the CivicTheme reference
  site (this site).
- MUST provide a static version of compiled Storybook for the CivicTheme-based
  consumer site.

## Agreements

- Config is stored in the `civictheme` theme's `config/install` and
  `config/optional` directories.
- Content types are prefixed with `civictheme_`.
- Field names are prefixed with `field_c_`.

## Custom Drupal site building scripts

Scripts in `scripts/custom` run after site is installed from profile in the
order they are numbered.

## Compiling theme assets

To compile all assets in all themes: `ahoy fe`

For development:
1. `civictheme_library`

       cd docroot/themes/contrib/civictheme/civictheme_library
       npm run build

2. `civictheme`

       cd docroot/themes/contrib/civictheme
       npm run build

2. `civictheme_demo`

       cd docroot/themes/custom/civictheme_demo
       npm run build

## Theme configuration export

Use shortcut command every time there is a configuration change to validate that
all new, updated or deleted configuration was captured

    ahoy local export-config

Configuration is captured into CivicTheme Drupal theme's `config/install` and
`config/optional` using Config Devel module.

To add new configuration to the export, add configuration name to `civictheme.info.yml`.

Tip: You can get the configuration name by exporting configuration with `drush cex -y`
to `config/default` and using file names without `.yml` extension. Do not forget
to remove all exported configuration files from `config/default` or the next site
install will fail.

Note that configuration for blocks in `civictheme` will be copied to `civictheme_demo` on
installation of `civictheme_demo`. We do not capture configuration for `civictheme_demo`.

Exclude certain configuration from automatically be added to `civictheme.info.yml`
by adding records to [theme_excluded_configs.txt](./scripts/theme_excluded_configs.txt).
Note that wildcards are supported.

## Demo content export

    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content <entity_type> <entity_id>

    # Example 1: export a single node with all dependencies
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content node 50

    # Example 2: export all terms, nodes and blocks and menu links for Default content.
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content taxonomy_term
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content node
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content block_content
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content menu_link_content

    # Example 3: export all terms, nodes, blocks and menu links for Corporate content.
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content taxonomy_term
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content node
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content block_content
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content menu_link_content
