# CivicTheme Drupal theme

Drupal component-based theme built on the CivicTheme UI Kit.

----

## Introduction

CivicTheme provides components and data structures to enhance end-user and
editorial experiences out-of-the-box.

The [UI kit](https://github.com/civictheme/uikit)
is a CMS-agnostic HTML/CSS/JS framework based on Atomic Design principles.

The Drupal theme provides full integration and ships with the UI Kit.

This theme is a [starterkit](https://www.drupal.org/docs/core-modules-and-themes/core-themes/starterkit-theme):
sites generate their own theme from it with core's `generate-theme` command
and own the result. The CivicTheme content model (content types, paragraph
types, fields, displays, views) ships separately as
[Drupal recipes](https://www.drupal.org/docs/extending-drupal/drupal-recipes)
applied at install time, so it stays
CivicTheme-owned and upgradable while the generated theme is yours. The
recipes live in the starterkit's `recipes/` directory and are applied from
there, never forked into generated themes.

## Installation

> [!IMPORTANT]
> For Drupal theme installation instructions, see [Installation instructions](https://docs.civictheme.io/installation/drupal-theme)

### Generating a site theme

Generate your site's theme from the starterkit, then install it:

```bash
php core/scripts/dr generate-theme your_theme --name "Your Theme" \
  --starterkit <starterkit_machine_name> --path themes/custom
```

Core renames the kit's identity (machine name, namespace, libraries, SDC
component namespace, hook classes) to your theme's. CivicTheme's content
model names — bundles, `field_c_*` fields, `civictheme_*` hooks and
provisioning functions, `ct-*` classes and the `@civictheme/uikit`
dependency — are deliberately not part of that identity and survive, so the
recipes and components keep working in the generated theme.

After generating, build the front end (this re-imports the UI Kit components
under your theme's component namespace):

```bash
cd themes/custom/your_theme && npm install
```

Then enable the theme and apply the content model recipes from the kit —
see `recipes/README.md` in the starterkit.

**Your theme, your responsibility**: the generated theme belongs to your
site. Components update through the `@civictheme/uikit` npm dependency
(`npm run uikit-install`); PHP-side improvements in the kit are adopted by
regenerating and porting your changes. There is no update hook path for
generated themes.

### Installing the kit directly

The kit is a working theme and can be enabled as-is — this is how the demo
and CI run it. Site builds should generate their own theme instead.

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
