Civic theme development
-----------------------

## Terminology
- Civic theme - Civic Drupal theme
- Civic Library - Civic front-end library, hosted in NPM.
- Demo theme, consumer theme, reference theme - a theme that uses Civic theme as
  a base theme
- Reference site, demo site - an example site (this repository) that uses Civic
  theme.


## Requirements and constraints
- Can be used out of the box without any customisations
- MUST NOT be changed for customisations. If customisations are required - a
  consumer theme MUST be created and used as a sub-theme of the Civic theme.
- Fully compatible with GovCMS 9:
  - MUST NOT have any modules
  - MUST NOT have any libraries
  - MUST NOT rely on GovCMS content structures
  - MUST assume that FE compilation happens on local machine and then committed
    to repository
- MUST provide a static version of compiled Storybook for the Civic site
- MUST provide a static version of compiled Storybook for the Consumer site


## Agreements
- Config is in the `civic` theme's `install` directory.
- Content types to be prefixed with `civic_`.


## Forklift

Currently, this repository contains (for ease of development):
1. Civic Drupal theme
2. Civic Demo Drupal theme
3. Civic Demo Drupal site installation based on GovCMS
4. Civic Library FE library

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
1. Allow adjusting Civic theme styling from the Drupal theme settings (V1.2).
2. Drupal sub-theme starter kit.
3. Integration with a quick install wizard.

## Compiling theme assets

To compile all assets in all themes: `ahoy fe`

For development:
1. `civic-library`

       cd docroot/themes/contrib/civic/civic-library
       npm run build

2. `civic`

       cd docroot/themes/contrib/civic
       npm run build

2. `civic_demo`

       cd docroot/themes/custom/civic_demo
       npm run build

## Theme configuration export

Configuration is captured into Civic Drupal theme's `config/install` and
`config/optional` with

    drush cde civic

To add new configuration to the export, add configuration name to `civic.info.yml`.

Tip: You can get the configuration name by exporting configuration with `drush cex -y`
to `config/default` and using file names without `.yml` extension. Do not forget
to remove all exported configuration files from `config/default` or the next site
install will fail.

Note that configuration for blocks in `civic` will be copied to `civic_demo` on
installation of `civic_demo`. We do not capture configuration for `civic_demo`.

## Demo content export

    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content <entity_type> <entity_id>

    # Example 1: export a single node with all dependencies
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content node 50

    # Example 2: export all nodes, blocks and menu links.
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content node
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content block_content
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content menu_link_content

    # Example 3: export all nodes, blocks and menu links.
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content node
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content block_content
    drush dcer --folder=modules/custom/civic_default_content/modules/civic_default_content_demo/content menu_link_content
