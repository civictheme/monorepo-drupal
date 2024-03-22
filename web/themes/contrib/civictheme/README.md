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

```bash
composer require drupal/civictheme
```

Enable CivicTheme theme to use it as-is - CivicTheme UI kit is
already included as a set of compiled assets.

## Creating a sub-theme from the CivicTheme theme

See [Sub-theme](https://docs.civictheme.io/development/drupal-theme/sub-theme)

## Updating CivicTheme

See [Version update](https://docs.civictheme.io/development/drupal-theme/version-update)

## Managing colors

See [Color selector](https://docs.civictheme.io/development/drupal-theme/color-selector)

## Development

### Switching to a new version of the UI Kit

The UI Kit is included as a dependency in the `package.json` file and then
"baked" into the theme as a part of the CI build and the release process.

Switching to a new version of the UI Kit usually take place during the release
of the Drupal theme.

```bash
# Switch to a new version v1.7.1
npm install --no-dev civictheme/uikit.git#v1.7.1
```

The development versions of the UI Kit are available as `main` or feature
branches and can be switched to during the development of the Drupal theme.

```bash
# Switch to a `main` branch
npm install --no-dev civictheme/uikit.git#main
```

```bash
# Switch to a feature branch feature/my-branch
npm install --no-dev civictheme/uikit.git#feature/my-branch
```

Note that this change would need to be reverted before merging the PR in the
Drupal theme so that the released version of the Drupal theme would use a
versioned release of the UI Kit.

---

For additional information, please refer to
the [Documentation site](https://docs.civictheme.io/drupal-theme)
