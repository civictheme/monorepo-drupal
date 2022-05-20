# CivicTheme - Development source site
Mono-repo to maintain CivicTheme and accompanying modules that are automatically published to another repositories on release.

[![CircleCI](https://circleci.com/gh/salsadigitalauorg/civictheme-source/tree/develop.svg?style=shield)](https://circleci.com/gh/salsadigitalauorg/civictheme-source/tree/develop)
![Drupal 9](https://img.shields.io/badge/Drupal-9-blue.svg)


[//]: # (DO NOT REMOVE THE BADGE BELOW. IT IS USED BY DREVOPS TO TRACK INTEGRATION)

[![DrevOps](https://img.shields.io/badge/DrevOps-9.x-blue.svg)](https://github.com/drevops/drevops/tree/9.x)

## Environments

- PROD: [https://nginx-php.master.civic.au2.amazee.io](https://nginx-php.master.civic.au2.amazee.io)
- DEV: [https://nginx-php.develop.civic.au2.amazee.io](https://nginx-php.develop.civic.au2.amazee.io)
- LOCAL: [http://civictheme.docker.amazee.io/](http://civictheme.docker.amazee.io/)

## Local environment setup
- Make sure that you have latest versions of all required software installed:
  - [Docker](https://www.docker.com/)
  - [Pygmy](https://github.com/pygmystack/pygmy)
  - [Ahoy](https://github.com/ahoy-cli/ahoy)
- Make sure that all local web development services are shut down (Apache/Nginx, Mysql, MAMP etc).
- Checkout project repository (in one of the [supported Docker directories](https://docs.docker.com/docker-for-mac/osxfs/#access-control)).



- Authenticate with Lagoon
  1. Create an SSH key and add it to your account in the [Lagoon Dashboard](https://ui-lagoon-master.ch.amazee.io/).
  2. Copy `default.env.local` to `.env.local`.
  3. Update `$DREVOPS_DB_DOWNLOAD_SSH_KEY_FILE` environment variable in `.env.local` file
  with the path to the SSH key.



- `pygmy up`
- `ahoy build`

### Apple M1 adjustments

Copy `default.docker-compose.override.yml` to `docker-compose.override.yml`.

## Build process

1. Builds fresh site from GovCMS Drupal profile. Use `ahoy install-site` to rebuild.
2. Enables additional modules required for development by installing `cs_core` module.
3. Enables CivicTheme theme and imports its configuration.
4. Creates CivicTheme Demo sub-theme using provided scaffolding script and sets it as a default theme.
5. Provisions content using Default Content module.
6. Enables `civictheme_govcms` module to remove out-of-the-box GovCMS content types.
7. Enables `civictheme_content` module to add default content to installation.

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

## CivicTheme Drupal theme

Please refer to [CivicTheme Drupal theme documentation](docroot/themes/contrib/civictheme/docs/introduction.md).

## CivicTheme components library
Please refer to [CivicTheme components Library documentation](docroot/themes/contrib/civictheme/civictheme-library/docs/introduction.md).

## Other resources

- [CivicTheme CMS-agnostic library](https://github.com/salsadigitalauorg/civictheme-library)
- [CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme-drupal)
- [Default content for CivicTheme](https://github.com/salsadigitalauorg/civictheme-drupal-content)
- [GovCMS adjustments for CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme-govcms)
