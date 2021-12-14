# Getting Started with Civic Drupal Theme

## Introduction

Get Started with Civic theme, the Drupal theme built on a decoupled component library implementing
a complete customisable design system with ready to use components, content types and editorial experience
compatible with GOV CMS.


## Quick start

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

Create your child theme - recommend using `drush generate theme` for an interactive wizerd for generating boilerplate.

Ensure the base theme is set as follows: `base theme: civic`

And add the following [components](https://www.drupal.org/project/components/) namespaces to
your `<child_theme>.info.yml` file.

```yaml

libraries-override:
  civic/global:
    css:
      theme:
        dist/civic.css: dist/styles.css
    js:
      dist/civic.js: dist/scripts.js

ckeditor_stylesheets:
  - dist/styles.css

# Child theme components. Any components not provided in this theme will be auto-discovered from the
# parent theme (Civic).
# Components may include parent theme's components using `@civic-` prefix in the namespace, e.g..  `@civic-atoms`.
components:
  namespaces:
    base:
      - components/00-base
    atoms:
      - components/01-atoms
    molecules:
      - components/02-molecules
    organisms:
      - components/03-organisms
    templates:
      - components/04-templates
    pages:
      - components/05-pages

```

### How to compile CivicTheme

Building the front-end

```bash
      cd docroot/themes/contrib/civic/civic-library && npm run build &&
      cd docroot/themes/contrib/civic && npm run build && 
      cd docroot/themes/custom/<child_theme> && npm run build
```

## Customise Civic Theme

Learn how to customise Civic theme with an expansive colour system and extensive range of options
to customise grid, spacing, fonts, and typography systems.

### Colours
### [Grid](docroot/themes/custom/civic/civic-library/docs/grid.md)


### How to add your theme colours

### How to change the colours in the components

### How to add a new component to your theme

#### Where to add components in the file system

#### How to link up templates within Drupal

### How to extend components

