# CivicTheme Starter Kit Drupal theme

Based on [CivicTheme](https://github.com/salsadigitalauorg/civictheme) Drupal theme.

Learn more about developing with CivicTheme in [CivicTheme documentation](../../contrib/civictheme/docs/README.md).

## Compiling front-end assets.

    npm install
    npm run build

## Linting code

    npm run lint

    npm run lint:fix

## Starting a local Storybook instance

    npm run storybook

## Managing colors

Website colors can be specified via:
- CSS code
- Color Selector
- CSS code with Color Selector overrides

See `admin/appearance/settings/civictheme_starter_kit` for more details about colors and to
set colors via Color Selector.

### Disabling Color Selector

If colors managed in CSS code only, make sure that Color Selector is disabled

    ./vendor/bin/drush config-set civictheme_starter_kit.settings colors.use_color_selector 0

### Setting colors via Drush command

Palette colors can be set in bulk via Drush command by providing Brand colors.

    # Enable Color Selector.
    ./vendor/bin/drush config-set civictheme_starter_kit.settings colors.use_color_selector 1

    # Enable Brand Colors.
    ./vendor/bin/drush config-set civictheme_starter_kit.settings colors.use_brand_colors 1

    # Set Brand Colors.
    ./vendor/bin/drush --include=path/to/civictheme/src/Drush civictheme:set-brand-colors light_brand1 light_brand2 light_brand3 dark_brand1 dark_brand2 dark_brand3

    # Purge dynamic assets cache. Will be rebuilt during next pageload.
    ./vendor/bin/drush --include=docroot/themes/contrib/civictheme/src/Drush civictheme:clear-cache

Example

    ./vendor/bin/drush -y config-set civictheme_starter_kit.settings colors.use_color_selector 1
    ./vendor/bin/drush -y config-set civictheme_starter_kit.settings colors.use_brand_colors 1
    ./vendor/bin/drush --include=docroot/themes/contrib/civictheme/src/Drush civictheme:set-brand-colors "#00698f" "#e6e9eb" "#121313" "#61daff" "#003a4f" "#00698f"
    ./vendor/bin/drush --include=docroot/themes/contrib/civictheme/src/Drush civictheme:clear-cache

## Updating site configuration after CivicTheme update

1. Check that your custom theme has 2 files:
   - `update_config.php` - script to update configurations.
   - `civictheme_starter_kit.site_custom_configs.txt` - file with configuration
      exclusions that are considered to be custom for the current site.
      This file contains some generic defaults.

2. Adjust custom configurations in `civictheme_starter_kit.site_custom_configs.txt`.
   These configurations will not be compared against configurations provided by the CivicTheme.
   Wildcards are supported.

3. Run the configuration update script:

    php ./scripts/update_config.php \
      ../../contrib/civictheme/config \
      ../../../config/default \
      scripts/civictheme_starter_kit.site_custom_configs.txt

4. Check updated configuration with a diff tool of your choice.
5. Resolve configuration overrides one-by-one.
6. Re-build local environment with updated configuration.
7. Check that everything looks good
8. If there are issues - repeat steps 2-7 until desired result is achieved.
