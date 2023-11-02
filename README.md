# CivicTheme - Development source site
Mono-repo used to maintain CivicTheme and accompanying modules that are automatically published to another repositories on release.

[![CircleCI](https://circleci.com/gh/salsadigitalauorg/civictheme_source.svg?style=shield)](https://circleci.com/gh/salsadigitalauorg/civictheme_source)
![Drupal 10](https://img.shields.io/badge/Drupal-10-blue.svg)

[![RenovateBot](https://img.shields.io/badge/RenovateBot-enabled-brightgreen.svg?logo=renovatebot)](https://renovatebot.com)


[//]: # (DO NOT REMOVE THE BADGE BELOW. IT IS USED BY DREVOPS TO TRACK INTEGRATION)

[![DrevOps](https://img.shields.io/badge/DrevOps-develop-blue.svg)](https://github.com/drevops/drevops/tree/develop)

[//]: # (Remove the section below once onboarding is finished)
## Onboarding
Use [Onboarding checklist](docs/onboarding.md) to track the project onboarding progress.

## Local environment setup
- Make sure that you have latest versions of all required software installed:
  - [Docker](https://www.docker.com/)
  - [Pygmy](https://github.com/pygmystack/pygmy)
  - [Ahoy](https://github.com/ahoy-cli/ahoy)
- Make sure that all local web development services are shut down (Apache/Nginx, Mysql, MAMP etc).
- Checkout project repository (in one of the [supported Docker directories](https://docs.docker.com/docker-for-mac/osxfs/#access-control)).



- Authenticate with Lagoon
  1. Create an SSH key and add it to your account in the [Lagoon Dashboard](https://ui-lagoon-master.ch.amazee.io/).
  2. Copy `.env.local.default` to `.env.local`.
  3. Update `$DREVOPS_DB_DOWNLOAD_SSH_KEY_FILE` environment variable in `.env.local` file
     with the path to the SSH key.




- `ahoy download-db`

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

- [PROD](https://default.civictheme.io)
- [DEV](https://defaultdev.civictheme.io)
- [LOCAL](http://civictheme-source.docker.amazee.io/)

### Content profiles

- [Corporate](https://nginx-php.content-corporate.civictheme-source.lagoon.salsa.hosting/)
- [Government](https://nginx-php.content-government.civictheme-source.lagoon.salsa.hosting/)
- [Higher Education](https://nginx-php.content-highereducation.civictheme-source.lagoon.salsa.hosting/)

## More about CivicTheme

- [CivicTheme UI kit](https://github.com/salsadigitalauorg/civictheme_library)
- [CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme)
- [Default content for CivicTheme](https://github.com/salsadigitalauorg/civictheme_content)
- [Admin adjustments for CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme_admin)
- [GovCMS adjustments for CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme_govcms)

---

For additional information, please refer to the [Documentation site](https://docs.civictheme.io/)
