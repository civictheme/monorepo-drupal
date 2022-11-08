# CivicTheme Drupal theme

----

Version: `{{ VERSION }}`

## Introduction

CivicTheme theme, the Drupal theme built using a standalone component library,
provides components and data structures to enhance editorial experiences
out-of-the-box.

The [standalone component library](https://github.com/salsadigitalauorg/civictheme_library)
is a CMS-agnostic HTML/CSS/JS framework based on Atomic Design principles.

The Drupal theme provides full integration with a library.

## Installation

    composer require salsadigitalauorg/civictheme

Enable CivicTheme theme to use it as-is - CivicTheme component library is
already included as a set of compiled assets.

## Creating a sub-theme from the CivicTheme theme

CivicTheme provides a starter theme and a script to generate a child theme for
you to get started with.

Run the following command from within `civictheme` theme directory:

    php civictheme_create_subtheme.php <theme_machine_name> "Human theme name" "Human theme description" /path/to/theme_machine_name

This will generate a sub-theme in 'path/to/theme_machine_name' theme directory
with everything ready to be installed and compiled.

## Compiling sub-theme assets

Run the following command from within your sub-theme directory:

    npm run build

## Managing colors

Website colors can be specified via:
- CSS code
- Color Selector
- CSS code with Color Selector overrides

See `admin/appearance/settings/civictheme` for more details about colors and to
set colors via Color Selector.

### Disabling Color Selector

If colors managed in CSS code only, make sure that Color Selector is disabled

    ./vendor/bin/drush config-set civictheme.settings colors.use_color_selector 0

### Setting colors via Drush command

Palette colors can be set in bulk via Drush command by providing Brand colors.

    # Enable Color Selector.
    ./vendor/bin/drush config-set civictheme.settings colors.use_color_selector 1

    # Enable Brand Colors.
    ./vendor/bin/drush config-set civictheme.settings colors.use_brand_colors 1

    # Set Brand Colors.
    ./vendor/bin/drush --include=path/to/civictheme/src/Drush civictheme:set-brand-colors light_brand1 light_brand2 light_brand3 dark_brand1 dark_brand2 dark_brand3

    # Purge dynamic assets cache. Will be rebuilt during next pageload.
    ./vendor/bin/drush --include=docroot/themes/contrib/civictheme/src/Drush civictheme:clear-cache

Example

    ./vendor/bin/drush -y config-set civictheme.settings colors.use_color_selector 1
    ./vendor/bin/drush -y config-set civictheme.settings colors.use_brand_colors 1
    ./vendor/bin/drush --include=docroot/themes/contrib/civictheme/src/Drush civictheme:set-brand-colors "#00698f" "#e6e9eb" "#121313" "#61daff" "#003a4f" "#00698f"
    ./vendor/bin/drush --include=docroot/themes/contrib/civictheme/src/Drush civictheme:clear-cache

## Updating CivicTheme

See `README.md` in your custom theme.

## Development documentation

- [CivicTheme Component Library Documentation](./civictheme_library/docs/README.md)
- [CivicTheme Drupal Theme Documentation](./docs/README.md)

----

## Other resources

- [CivicTheme Source site](https://github.com/salsadigitalauorg/civictheme_source)
- [CivicTheme CMS-agnostic library](https://github.com/salsadigitalauorg/civictheme_library)
