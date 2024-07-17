# CivicTheme Drupal theme

Drupal 10 component-based theme.

----

## Introduction

CivicTheme theme, the Drupal theme with the UI Kit integration, provides
components and data structures to enhance end-user and editorial experiences
out-of-the-box.

The [UI kit](https://github.com/civictheme/uikit)
is a CMS-agnostic HTML/CSS/JS framework based on Atomic Design principles.

The Drupal theme provides full integration and ships with the UI Kit.

## Installation

> [!IMPORTANT]
> For Drupal theme installation instructions, see https://docs.civictheme.io/development/drupal-theme

Enable CivicTheme theme to use it as-is - CivicTheme UI kit is
already included as a set of compiled assets.

## Creating a sub-theme from the CivicTheme theme

See [Sub-theme](https://docs.civictheme.io/development/drupal-theme/sub-theme)

## Updating CivicTheme

See [Version update](https://docs.civictheme.io/development/drupal-theme/version-update)

## Managing colors

See [Color selector](https://docs.civictheme.io/development/drupal-theme/color-selector)

## Development

### Local development

Provided that you have PHP installed locally, you can develop an extension using
the provided scripts.

#### Build

Run `.devtools/assemble.sh` (or `ahoy assemble`
if [Ahoy](https://github.com/ahoy-cli/ahoy) is installed) to start inbuilt PHP
server locally and run the same commands as in CI, plus installing a site and
your extension automatically.

#### Code linting

Run tools individually (or `ahoy lint` to run all tools
if [Ahoy](https://github.com/ahoy-cli/ahoy) is installed) to lint your code
according to
the [Drupal coding standards](https://www.drupal.org/docs/develop/standards).

```
cd build

vendor/bin/phpcs
vendor/bin/phpstan
vendor/bin/rector --clear-cache --dry-run
vendor/bin/phpmd . text phpmd.xml
vendor/bin/twig-cs-fixer
```

- PHPCS config: [`phpcs.xml`](phpcs.xml)
- PHPStan config: [`phpstan.neon`](phpstan.neon)
- PHPMD config: [`phpmd.xml`](phpmd.xml)
- Rector config: [`rector.php`](rector.php)
- Twig CS Fixer config: [`.twig-cs-fixer.php`](.twig-cs-fixer.php)

### Browsing SQLite database

To browse the contents of created SQLite database
(located at `/tmp/site_[EXTENSION_NAME].sqlite`),
use [DB Browser for SQLite](https://sqlitebrowser.org/).

### Switching to a new version of the UI Kit

The UI Kit is included as a dependency in the `package.json` file and then
"baked" into the theme as a part of the CI build and the release process.

Switching to a new version of the UI Kit usually take place during the release
of the Drupal theme.

```bash
# Switch to a new version v1.8.0
npm install --no-dev civictheme/uikit.git#v1.8.0
```

The development versions of the UI Kit are available as `main` or feature
branches and can be switched to during the development of the Drupal theme.

```bash
# Switch to a `main` branch
npm install --no-dev civictheme/uikit.git#main
# Run postinstall script if it has not run.
npm run postinstall
```

```bash
# Switch to a feature branch feature/my-branch
npm install --no-dev civictheme/uikit.git#feature/my-branch
# Run postinstall script if it has not run.
npm run postinstall
```

Note that this change would need to be reverted before merging the PR in the
Drupal theme so that the released version of the Drupal theme would use a
versioned release of the UI Kit.

### Updating minor dependencies

```bash
npm install -g npm-check-updates
npx npm-check-updates -u --target minor
```

---

For additional information, please refer to
the [Documentation site](https://docs.civictheme.io/drupal-theme)
