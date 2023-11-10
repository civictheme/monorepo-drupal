# Testing

## Authoring tests

This repository uses different types of tests:
1. Unit, Functional and Kernel tests for Drupal modules and themes.
2. Bats tests to test tooling and scripts.
3. Behat tests to test overall end-to-end Drupal site functionality.

### Behat tests

Behat configuration uses multiple extensions:
- [Drupal Behat Extension](https://github.com/jhedstrom/drupalextension) - Drupal integration layer. Allows to work with Drupal API from within step definitions.
- [Behat Screenshot Extension](https://github.com/integratedexperts/behat-screenshot) - Behat extension and a step definition to create HTML and image screenshots on demand or test fail.
- [Behat Progress Fail Output Extension](https://github.com/integratedexperts/behat-format-progress-fail) - Behat output formatter to show progress as TAP and fail messages inline. Useful to get feedback about failed tests while continuing test run.
- `FeatureContext` - Site-specific context with custom step definitions.

Add `@skipped` tag to failing tests if you would like to skip them.

### Authoring schema update tests

> Available from CivicTheme 1.5

CivicTheme provides configuration for content types, fields and site settings.
These can change with versions of the theme. To ensure that the changes are
applied correctly in the consumer site's database, we use
[schema update tests](web/themes/contrib/civictheme/tests/src/Functional/Update).

These types of tests require a database of the site with the previous
version of the module or theme installed to be available to the test.

In CivicTheme, we use "bare" and "filled" database dumps to test schema updates:
- "bare" database dump contains only the schema and no content.
- "filled" database dump contains the schema and the content.

For simplicity, we only test on the `minimal` profile and the latest Drupal version.

To update the database dumps:

1. Checkout this repository at the specific CivicTheme release (1.3 or newer).
   Note that some of the environment variables are only available in the latest
   version of the repository and you may need to adjust them below to the version
   you are using (e.g. `SKIP_LIBRARY_INSTALL` was in version `1.3.2` and now is
   called `CIVICTHEME_LIBRARY_INSTALL_SKIP` in `1.5.0`).
2. Update "bare" database dump:
   ```bash
   export CIVICTHEME_VERSION=<civictheme_version> # update to your version
   export DRUPAL_VERSION=10
   export DRUPAL_VERSION_FULL=10.0.0-rc1
   export DRUPAL_PROFILE=minimal
   DREVOPS_PROVISION_POST_OPERATIONS_SKIP=1 ahoy build
   ahoy cli "DRUPAL_PROFILE=minimal scripts/custom/drupal-install-site-1-enable-modules.sh"
   mkdir -p web/themes/contrib/civictheme/tests/fixtures/updates
   ahoy cli php web/core/scripts/dump-database-d8-mysql.php | gzip > "web/themes/contrib/civictheme/tests/fixtures/updates/drupal_${DRUPAL_VERSION_FULL}.${DRUPAL_PROFILE}.civictheme_${CIVICTHEME_VERSION}.bare.php.gz"
   ```
3. Update "filled" database dump:
   ```bash
   export CIVICTHEME_VERSION=<civictheme_version> # update to your version
   export DRUPAL_VERSION=10
   export DRUPAL_VERSION_FULL=10.0.0-rc1
   export DRUPAL_PROFILE=minimal
   DREVOPS_PROVISION_POST_OPERATIONS_SKIP=1 ahoy build
   ahoy cli "DRUPAL_PROFILE=minimal scripts/custom/drupal-install-site-1-enable-modules.sh"
   ahoy cli "drush php:eval -v \"require_once '/app/web/themes/contrib/civictheme/theme-settings.provision.inc'; civictheme_provision_cli();\""
   ahoy cli "GENERATED_CONTENT_CREATE=1 drush pm:enable cs_generated_content -y"
   ahoy cli "GENERATED_CONTENT_DELETE_SKIP=1 drush pm:uninstall cs_generated_content generated_content -y"
   mkdir -p web/themes/contrib/civictheme/tests/fixtures/updates
   ahoy cli php web/core/scripts/dump-database-d8-mysql.php | gzip > "web/themes/contrib/civictheme/tests/fixtures/updates/drupal_${DRUPAL_VERSION_FULL}.${DRUPAL_PROFILE}.civictheme_${CIVICTHEME_VERSION}.filled.php.gz"
   ```
