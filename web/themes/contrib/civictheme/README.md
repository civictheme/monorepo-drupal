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
> For Drupal theme installation instructions, see [Installation instructions](https://docs.civictheme.io/installation/drupal-theme)

Enable CivicTheme theme to use it as-is - CivicTheme UI kit is
already included as a set of compiled assets.

## Creating a sub-theme from the CivicTheme theme

See [Sub-theme](https://github.com/civictheme/docs/blob/main/development/drupal-theme/sub-theme.md)

## Structured data (JSON-LD)

CivicTheme can emit Schema.org JSON-LD structured data in the page head of
canonical, published node pages. The feature is **disabled by default** and is
enabled in _Appearance → CivicTheme settings → Structured data (JSON-LD)_.

A single `<script type="application/ld+json">` element is emitted containing an
`@graph` with:

- `Organization` - the publisher and author, using the site name, the configured
  logo and the official profile URLs.
- `WebSite` - linked to the Organization.
- The content entity - `WebPage`, one of the article types (`Article`,
  `NewsArticle`, `BlogPosting`, `Report`) or `Event`, depending on the type
  mapped to the content type.
- `BreadcrumbList` - mirrors the breadcrumb rendered by the Banner component
  (the site breadcrumb followed by the current page), omitted when it would only
  contain a single item.

Settings:

| Setting | Description |
| --- | --- |
| Enable JSON-LD structured data | Master switch for the feature. |
| Content type mapping | Schema.org type per content type. Content types left as _None_ receive no markup. |
| Organization | Official profile URLs (`sameAs`) and the path to a raster logo. The organization name comes from the site name. |
| Image style | Image style applied to the featured image. Defaults to _Social share_ (1200x630). |
| Description length | Maximum length of the description sourced from the content summary. |

Values are read from the standard CivicTheme fields when they exist on the
bundle - `field_c_n_summary`, `field_c_n_thumbnail`, `field_c_n_topics`,
`field_c_n_custom_last_updated` and, for events, `field_c_n_date_range` and
`field_c_n_location`.

Site-specific properties, additional graph nodes and additional Schema.org types
are added from a sub-theme or a module with
`hook_civictheme_structured_data_alter()` and
`hook_civictheme_structured_data_types_alter()` - see
[civictheme.api.php](civictheme.api.php).

The output can be validated with the
[Schema Markup Validator](https://validator.schema.org) or Google's
[Rich Results Test](https://search.google.com/test/rich-results).

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
