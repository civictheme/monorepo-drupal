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
- Can be used out of the box without any customizations
- MUST not be changed for customizations. If customizations are required - a
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
The forklifted repository will have the following costraints:
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
