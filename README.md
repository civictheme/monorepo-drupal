# Civic Demo
Drupal 9 implementation of Civic Demo site

[![CircleCI](https://circleci.com/gh/salsadigitalauorg/civic/tree/develop.svg?style=svg&circle-token=abf9bde8507c968b4de120552682aa925d979256)](https://circleci.com/gh/salsadigitalauorg/civic/tree/develop)
![Drupal 9](https://img.shields.io/badge/Drupal-9-blue.svg)


[//]: # (DO NOT REMOVE THE BADGE BELOW. IT IS USED BY DREVOPS TO TRACK INTEGRATION)

[![DrevOps](https://img.shields.io/badge/DrevOps-9.x-blue.svg)](https://github.com/drevops/drevops/tree/9.x)

## Environments

- PROD: [https://civic:civic2021@nginx-php.master.civic.au2.amazee.io](https://civic:civic2021@nginx-php.master.civic.au2.amazee.io)
- DEV: [https://civic:civic2021@nginx-php.develop.civic.au2.amazee.io](https://civic:civic2021@nginx-php.develop.civic.au2.amazee.io)
- LOCAL: [http://civictheme.docker.amazee.io/](http://civictheme.docker.amazee.io/)

## Local environment setup

- Make sure that you have the latest versions of all required software installed:
  - [Docker](https://www.docker.com/)
  - [Pygmy](https://pygmy.readthedocs.io/)
  - [Ahoy](https://github.com/ahoy-cli/ahoy)
- Make sure that all local web development services are shut down (Apache/Nginx, Mysql, MAMP etc).
- Checkout project repository (in one of the [supported Docker directories](https://docs.docker.com/docker-for-mac/osxfs/#access-control)).
- `pygmy up`
- `ahoy build`

### Apple M1 adjustments

The AIO supported version of Pygmy does not appear to receive updates any longer,
appears pygmy-go is the de-facto standard going forward. There is not a stable
release with Apple Silicon support yet, however the following dev branch is
entirely functional, so we need to build from sources.

```
pygmy down && pygmy-go down
git clone --branch arm_testing git@github.com:tobybellwood/pygmy-go.git ./pygmy-go-dev
cd ./pygmy-go-dev
make build
cd builds
cp pygmy-go-darwin-arm64 $(which pygmy)
pygmy version # should output 'Pygmy version unidentifiable.'
pygmy up
```

Copy `default.docker-compose.override.yml` to `docker-compose.override.yml`.

## Build process

1. Builds fresh site from GovCMS Drupal profile. Use `ahoy install-site` to rebuild.
2. Enables additional modules required for development by installing `cd_core` module.
3. Enables Civic theme and imports its configuration.
4. Creates Civic Demo sub-theme using provided scaffolding script and sets it as a default theme.
5. Provisions content using Default Content module.
6. Enables `civic_companion` module to remove out-of-the-box GovCMS content types.

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

## Development
Please refer to [development documentation](DEVELOPMENT.md).

## Testing
Please refer to [testing documentation](TESTING.md).

## CI
Please refer to [CI documentation](CI.md).

## Deployment
Please refer to [deployment documentation](DEPLOYMENT.md).

## Releasing
Please refer to [releasing documentation](RELEASING.md).

## FAQs
Please refer to [FAQs](FAQs.md).

## Civic Drupal theme
Please refer to [Civic Drupal theme documentation](docroot/themes/contrib/civic/docs/introduction.md).

## Civic components library
Please refer to [Civic components Library documentation](docroot/themes/contrib/civic/civic-library/docs/introduction.md).
