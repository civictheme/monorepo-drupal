# Getting Started with Civic Drupal theme

## Introduction

Civic theme, the Drupal theme built using a standalone component library,
provides components and data structures to enhance editorial experiences
out-of-the-box.

The standalone component library is a CMS-agnostic HTML/CSS/JS framework based
on Atomic Design principles.

The Drupal theme provides full integration with a library.

## Installation

Add the following entries to your `composer.json` in your Drupal site root
```json
{
  "repositories": {
    "civic": {
      "type": "vcs",
      "url": "git@github.com:salsadigitalauorg/civic-theme.git"
    }
  }
}
```

Run the following command:

    composer require salsadigitalauorg/civic

Enable Civic theme to use it as-is - Civic component library is alrady included
as compiled assets.

## Creating a sub-theme from Civic theme

Civic provides a starter theme to generate a child theme for you to get started
with.

Run the following command from within `civic` theme directory:

    php civic-create-subtheme.php <theme_machine_name> "Human theme name" "Human theme description"

This will generate a sub-theme in your `custom` theme directory with everything
ready to be installed and compiled.

## Compiling sub-theme assets

Run the following command from within your sub-theme directory:

    npm run build

## Development documentation

- [Civic Component Library Documentation](./civic-library/docs/introduction.md)
- [Civic Drupal Theme Documentation](./docs/introduction.md)
