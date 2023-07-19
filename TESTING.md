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
[schema update tests](docroot/themes/contrib/civictheme/tests/src/Functional/Update).

These types of tests tests require a database of the site with the previous
version of the module or theme installed to be available to the test.

In CivicTheme, we use "bare" and "filled" database dumps to test schema updates:
- "bare" database dump contains only the schema and no content.
- "filled" database dump contains the schema and the content.

For simplicity, we only test on the `minimal` profile and the latest Drupal version.

To update the database dumps:

1. Checkout this repository at the specific CivicTheme release (1.3 or newer):
2. Update bare database dump:
   ```bash
   export CIVICTHEME_VERSION=<civictheme_version> # update to your version
   export DREVOPS_DRUPAL_VERSION=10
   export DREVOPS_DRUPAL_PROFILE=minimal
   CIVICTHEME_SUBTHEME_ACTIVATION_SKIP=1 CIVICTHEME_LIBRARY_INSTALL_SKIP=1 GENERATED_CONTENT_CREATE_SKIP=1 ahoy build
   ahoy cli php docroot/core/scripts/dump-database-d8-mysql.php | gzip >  "docroot/themes/contrib/civictheme/tests/fixtures/updates/drupal_${DREVOPS_DRUPAL_VERSION}.${DREVOPS_DRUPAL_PROFILE}.civictheme_${DREVOPS_DRUPAL_VERSION}.bare.php.gz"
   ```
3. Update filled database dump:
   ```bash
   export CIVICTHEME_VERSION=<civictheme_version> # update to your version
   export DREVOPS_DRUPAL_VERSION=10
   export DREVOPS_DRUPAL_PROFILE=minimal
   CIVICTHEME_SUBTHEME_ACTIVATION_SKIP=1 CIVICTHEME_LIBRARY_INSTALL_SKIP=1 ahoy build
   GENERATED_CONTENT_DELETE_SKIP=1 ahoy pm:uninstall cs_generated_content generated_content -y
   ahoy cli php docroot/core/scripts/dump-database-d8-mysql.php | gzip >  "docroot/themes/contrib/civictheme/tests/fixtures/updates/drupal_${DREVOPS_DRUPAL_VERSION}.${DREVOPS_DRUPAL_PROFILE}.civictheme_${DREVOPS_DRUPAL_VERSION}.filled.php.gz"
   ```
