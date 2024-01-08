<p align="center">
  <a href="" rel="noopener">
  <img height=100px src="web/themes/contrib/civictheme/assets/logos/logo_secondary_light_mobile.png" alt="CivicTheme logo"></a>
</p>

<h1 align="center">CivicTheme - Development source site</h1>

<p>Mono-repo used to maintain CivicTheme and accompanying modules that are automatically published to another repositories on release.</p>

<div align="center">

[![GitHub Issues](https://img.shields.io/github/issues/civictheme/monorepo-drupal.svg)](https://github.com/civictheme/monorepo-drupal/issues)
[![GitHub Pull Requests](https://img.shields.io/github/issues-pr/civictheme/monorepo-drupal.svg)](https://github.com/civictheme/monorepo-drupal/pulls)
![GitHub release (latest by date)](https://img.shields.io/github/v/release/civictheme/monorepo-drupal)
[![CircleCI](https://circleci.com/gh/civictheme/monorepo-drupal.svg?style=shield)](https://circleci.com/gh/civictheme/monorepo-drupal)
![Drupal 10](https://img.shields.io/badge/Drupal-10-blue.svg)
![LICENSE](https://img.shields.io/github/license/civictheme/monorepo-drupal)
[![RenovateBot](https://img.shields.io/badge/RenovateBot-enabled-brightgreen.svg?logo=renovatebot)](https://renovatebot.com)

[//]: # (DO NOT REMOVE THE BADGE BELOW. IT IS USED BY DREVOPS TO TRACK INTEGRATION)

[![DrevOps](https://img.shields.io/badge/DrevOps-1.17.2-blue.svg)](https://github.com/drevops/drevops/tree/1.17.2)

</div>

---

> [!IMPORTANT]
> For Drupal theme installation instructions into your site, see https://docs.civictheme.io/development/drupal-theme

## Local environment setup

- Make sure that you have latest versions of all required software installed:
  - [Docker](https://www.docker.com/)
  - [Pygmy](https://github.com/pygmystack/pygmy)
  - [Ahoy](https://github.com/ahoy-cli/ahoy)
- Make sure that all local web development services are shut down (Apache/Nginx, Mysql, MAMP etc).
- Checkout project repository (in one of the [supported Docker directories](https://docs.docker.com/docker-for-mac/osxfs/#access-control)).
- `pygmy up`
- `ahoy build`

### Apple M1 adjustments

Copy `docker-compose.override.default.yml` to `docker-compose.override.yml`.

## Project documentation

- [Development](docs/development.md)
- [FAQs](docs/faqs.md)
- [Testing](docs/testing.md)
- [CI](docs/ci.md)
- [Releasing](docs/releasing.md)
- [Deployment](docs/deployment.md)

## Environments

- [PROD](https://default.civictheme.io) - use this as a reference for the latest stable version
- [DEV](https://defaultdev.civictheme.io)

### Content profiles

- [Corporate](https://nginx-php.content-corporate.monorepo-drupal.lagoon.salsa.hosting/)
- [Government](https://nginx-php.content-government.monorepo-drupal.lagoon.salsa.hosting/)
- [Higher Education](https://nginx-php.content-highereducation.monorepo-drupal.lagoon.salsa.hosting/)

## More about CivicTheme

- [Documentation](https://docs.civictheme.io/)
- [CivicTheme UI kit](https://github.com/civictheme/uikit)
- [CivicTheme Drupal theme](https://www.drupal.org/project/civictheme)
- [Default content for CivicTheme Drupal theme](https://github.com/civictheme/civictheme_content)
- [Admin adjustments for CivicTheme Drupal theme](https://github.com/civictheme/civictheme_admin)
- [GovCMS adjustments for CivicTheme Drupal theme](https://github.com/civictheme/civictheme_govcms)

