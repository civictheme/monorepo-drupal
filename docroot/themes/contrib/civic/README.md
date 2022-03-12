# Getting Started with Civic Drupal Theme

## Introduction

Get Started with Civic theme, the Drupal theme built on a decoupled component library implementing
a complete customisable design system with ready to use components, content types and editorial experience
compatible with GOV CMS.

### Table of Contents

1. [Quick start](#quick-start)
2. [Extending, modifying and customising Civic](#customising-civic)


## <span id="quick-start">Quick start</span>

### Installing Civic theme - Composer installation

Add the following entries to your `composer.json` in your root installation directory
and install your Drupal site to install the required Civic theme package.

```json
{
  "require" : {
    "salsadigitalauorg/civic": "^1.0"
  },
  "repositories": {
    "civic": {
      "type": "vcs",
      "url": "git@github.com:salsadigitalauorg/civic-theme.git"
    }
  }
}

```

For using civic without colour changes or customisations - enable civic and create your site.

### Create your Civic Consumer (child) Theme

Civic provides a starter theme to generate a child theme for you to get started with.

Run the following command from within `civic` theme directory:

`php civic-create-subtheme.php <theme_machine_name> "Human theme name" "Human theme description"`

This will generate a child theme in your `custom` theme directory with everything ready to be installed and compiled.

**Important - Civic starter theme also creates demo / example components - you may wish to delete these components
before beginning development. **

### How to compile CivicTheme

Building the front-end

```bash
      cd docroot/themes/contrib/civic/civic-library && npm run build &&
      cd docroot/themes/contrib/civic && npm run build && 
      cd docroot/themes/custom/<child_theme> && npm run build
```

This now will provide the boilerplate for a fully functioning consumer theme of Civic. Please read the customising
Civic documentation to learn how to extend, modify and customise Civic.

## <span id="customising-civic">Extending, modifying and customising Civic</span>

Learn how to customise Civic theme with an expansive colour system and extensive range of options
to customise grid, spacing, fonts, and typography systems.

Civic provides two systems:

- Component library - the twig and front-end templates
- Civic Drupal theme - the drupal theme implementing the component library

### Technical documentation

We strongly recommend reading the documentation which attempts to provide an overview of concepts and architecture.

There are two parts to this documentation - the component library and the drupal theme.

#### [Civic Component Library Documentation](./civic-library/docs/introduction.md)

#### [Civic Drupal Theme Documentation](./docs/introduction.md)
