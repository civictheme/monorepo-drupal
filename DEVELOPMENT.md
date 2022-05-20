CivicTheme theme development
-----------------------

## Terminology
- CivicTheme theme - CivicTheme Drupal theme
- CivicTheme Library - CivicTheme front-end library, hosted in NPM.
- Demo theme, consumer theme, reference theme - a theme that uses CivicTheme theme as
  a base theme
- Reference site, demo site - an example site (this repository) that uses CivicTheme
  theme.


## Requirements and constraints
- Can be used out of the box without any customisations
- MUST NOT be changed for customisations. If customisations are required - a
  consumer theme MUST be created and used as a sub-theme of the CivicTheme theme.
- Fully compatible with GovCMS 9:
  - MUST NOT have any modules
  - MUST NOT have any libraries
  - MUST NOT rely on GovCMS content structures
  - MUST assume that FE compilation happens on local machine and then committed
    to repository
- MUST provide a static version of compiled Storybook for the CivicTheme site
- MUST provide a static version of compiled Storybook for the Consumer site


## Agreements
- Config is in the `civictheme` theme's `install` directory.
- Content types to be prefixed with `civictheme_`.


## Forklift

Currently, this repository contains (for ease of development):
1. CivicTheme Drupal theme
2. CivicTheme Demo Drupal theme
3. CivicTheme Demo Drupal site installation based on GovCMS
4. CivicTheme Library FE library

Once active development phase is finished, this repository will be "forklifted"
to only contain theme code.
The forklifted repository will have the following constraints:
1. Installable for PHP 7.4 and PHP 8 + tests
2. Passing all code quality checks
3. Having all configuration captured
4. Having tests to install standard Drupal profile from the source and install the theme.
5. Having tests to install GovCMS Drupal profile from the source and install the theme.
6. Having Behat tests to assert provided configuration
7. Having static version of the compiled storybook components
8. Able to preview compiled storybook components

## Roadmap
1. Allow adjusting CivicTheme theme styling from the Drupal theme settings (V1.2).
2. Drupal sub-theme starter kit.
3. Integration with a quick install wizard.

## Compiling theme assets

To compile all assets in all themes: `ahoy fe`

For development:
1. `civictheme-library`

       cd docroot/themes/contrib/civictheme/civictheme-library
       npm run build

2. `civictheme`

       cd docroot/themes/contrib/civictheme
       npm run build

2. `civictheme_demo`

       cd docroot/themes/custom/civictheme_demo
       npm run build

## Theme configuration export

Use shortcut command

    ahoy export-config

Configuration is captured into CivicTheme Drupal theme's `config/install` and
`config/optional` with

    drush cde civictheme

To add new configuration to the export, add configuration name to `civictheme.info.yml`.

Tip: You can get the configuration name by exporting configuration with `drush cex -y`
to `config/default` and using file names without `.yml` extension. Do not forget
to remove all exported configuration files from `config/default` or the next site
install will fail.

Note that configuration for blocks in `civictheme` will be copied to `civictheme_demo` on
installation of `civictheme_demo`. We do not capture configuration for `civictheme_demo`.

## Demo content export

    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content <entity_type> <entity_id>

    # Example 1: export a single node with all dependencies
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content node 50

    # Example 2: export all terms, nodes and blocks for Default content.
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content taxonomy_term
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content node
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content block_content
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_default/content menu_link_content

    # Example 3: export all terms, nodes, blocks and menu links for Corporate content.
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content taxonomy_term
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content node
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content block_content
    drush dcer --folder=modules/custom/civictheme_content/modules/civictheme_content_corporate/content menu_link_content
