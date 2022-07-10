# CivicTheme Starter Kit Drupal theme

Based on [CivicTheme](https://github.com/salsadigitalauorg/civictheme) Drupal theme.

Learn more about developing with CivicTheme in [CivicTheme documentation](../../contrib/civictheme/docs/README.md).

## Compiling front-end assets.

    npm ci
    npm run build

## Linting code

    npm run lint

    npm run lint:fix

## Starting a local Storybook instance

    npm run storybook

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
