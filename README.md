# Civic Demo
Drupal 9 implementation of Civic Demo site

[![CircleCI](https://circleci.com/gh/salsadigitalauorg/civic/tree/develop.svg?style=svg&circle-token=abf9bde8507c968b4de120552682aa925d979256)](https://circleci.com/gh/salsadigitalauorg/civic/tree/develop)
![Drupal 9](https://img.shields.io/badge/Drupal-9-blue.svg)


[//]: # (DO NOT REMOVE THE BADGE BELOW. IT IS USED BY DREVOPS TO TRACK INTEGRATION)

[![DrevOps](https://img.shields.io/badge/DrevOps-9.x-blue.svg)](https://github.com/drevops/drevops/tree/9.x)

## Environments

- PROD: [https://civic:civic2021@nginx-php.master.civic.au2.amazee.io](https://civic:civic2021@nginx-php.master.civic.au2.amazee.io)
- DEV: [https://civic:civic2021@nginx-php.develop.civic.au2.amazee.io](https://civic:civic2021@nginx-php.develop.civic.au2.amazee.io)
- LOCAL: [http://civic-demo.docker.amazee.io/](http://civic-demo.docker.amazee.io/)

## Local environment setup

- Make sure that you have latest versions of all required software installed:
  - [Docker](https://www.docker.com/)
  - [Pygmy](https://pygmy.readthedocs.io/)
  - [Ahoy](https://github.com/ahoy-cli/ahoy)
- Make sure that all local web development services are shut down (Apache/Nginx, Mysql, MAMP etc).
- Checkout project repository (in one of the [supported Docker directories](https://docs.docker.com/docker-for-mac/osxfs/#access-control)).
- `pygmy up`
- `ahoy build`

## Build process

1. Builds fresh site from GovCMS Drupal profile. Use `ahoy install-site` to rebuild.
2. Enables additional modules required for development by installing `cd_core` module.
3. Enables Civic theme and imports its configuration.
4. Enables Civic Demo theme and sets it as a default theme.
5. Provisions content using Default Content module.

See sections below on using development tools.

## Available `ahoy` commands

Run `ahoy help` for a full list of commands.

Most used commands:

    build        Build or rebuild the project.
    install-site Install or re-install a site.
    info         Show information about this project.
    login        Login to a website.

    cli          Start a shell or run a command inside the CLI service container.
    debug        Enable debug configuration.
    lint         Lint back-end and front-end code.
    test-bdd     Run BDD tests.

## Behat tests

Behat configuration uses multiple extensions:
- [Drupal Behat Extension](https://github.com/jhedstrom/drupalextension) - Drupal integration layer. Allows to work with Drupal API from within step definitions.
- [Behat Screenshot Extension](https://github.com/integratedexperts/behat-screenshot) - Behat extension and a step definition to create HTML and image screenshots on demand or test fail.
- [Behat Progress Fail Output Extension](https://github.com/integratedexperts/behat-format-progress-fail) - Behat output formatter to show progress as TAP and fail messages inline. Useful to get feedback about failed tests while continuing test run.
- `FeatureContext` - Site-specific context with custom step definitions.

Add `@skipped` tag to failing tests if you would like to skip them.

## Automated builds (Continuous Integration)

In software engineering, continuous integration (CI) is the practice of merging all developer working copies to a shared mainline several times a day.
Before feature changes can be merged into a shared mainline, a complete build must run and pass all tests on CI server.

This project uses [Circle CI](https://circleci.com/) as a CI server: it imports production backups into fully built codebase and runs code linting and tests. When tests pass, a deployment process is triggered for nominated branches (usually, `master` and `develop`).

Add `[skip ci]` to the commit subject to skip CI build. Useful for documentation changes.

## Development

### Compiling theme assets

To compile all assets in all themes: `ahoy fe`

For development:
1. `civic-library`

       cd docroot/themes/custom/civic/civic-library
       npm run build

2. `civic`

       cd docroot/themes/custom/civic
       npm run build

2. `civic_demo`

       cd docroot/themes/custom/civic_demo
       npm run build

### Theme configuration export

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

### Demo content export

    drush dcer --folder=modules/custom/cd_core/content <entity_type> <entity_id>

    # Example
    drush dcer --folder=modules/custom/cd_core/content node 50

## FAQs

Please refer to [FAQs](FAQs.md)
