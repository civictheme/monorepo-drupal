# CivicTheme Drupal theme

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

    php civictheme_create_subtheme.php <theme_machine_name> "Human theme name" "Human theme description"

This will generate a sub-theme in your `custom` theme directory with everything
ready to be installed and compiled.

## Compiling sub-theme assets

Run the following command from within your sub-theme directory:

    npm run build

## Development documentation

- [CivicTheme Component Library Documentation](./civictheme_library/docs/introduction.md)
- [CivicTheme Drupal Theme Documentation](./docs/introduction.md)

----

## Other resources

- [CivicTheme Source site](https://github.com/salsadigitalauorg/civictheme_source)
- [CivicTheme CMS-agnostic library](https://github.com/salsadigitalauorg/civictheme_library)
