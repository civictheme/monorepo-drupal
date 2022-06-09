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

    php ./scripts/update_config.php ../../contrib/civictheme/config ../../../config/default scripts/example.site_custom_configs.txt

Note that this script can be run on your host, and it does not require a running
site to copy config files. You will need to import and export config using a
running site though after the config files are updated.
