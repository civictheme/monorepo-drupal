CivicTheme Drupal theme development
-----------------------

See documentation for [UI kit](https://docs.civictheme.io/ui-kit) and [Drupal theme](https://docs.civictheme.io/drupal-theme) first.

## Custom Drupal site building scripts

Scripts in `scripts/custom` run after site is installed from profile in the
order they are numbered.

## Compiling theme assets

To compile all assets in all themes: `ahoy fe`

For development:
1. `civictheme_library`

       cd docroot/themes/contrib/civictheme/civictheme_library
       npm run build

2. `civictheme`

       cd docroot/themes/contrib/civictheme
       npm run build

2. `civictheme_demo`

       cd docroot/themes/custom/civictheme_demo
       npm run build

## Configuration export

Use shortcut command every time there is a configuration change to validate that
all new, updated or deleted configuration was captured

    ahoy export-config

Configuration is captured using Config Devel module for:
- development modules into `civictheme_dev` module's `config/install` and `config/optional` directories.
- theme into CivicTheme Drupal theme's `config/install` and `config/optional` directories.

To add new configuration to the export, add configuration name to `civictheme.info.yml`.

Tip: You can get the configuration name by exporting configuration with `drush cex -y`
to `config/default` and using file names without `.yml` extension. Do not forget
to remove all exported configuration files from `config/default` or the next site
install will fail. But this all is already handled in `ahoy export-config`.

Note that configuration for blocks in `civictheme` will be copied to `civictheme_demo` on
installation of `civictheme_demo`. We do not capture configuration for `civictheme_demo`.

Exclude certain configuration from automatically be added to `civictheme.info.yml`
by adding records to [theme_excluded_configs.txt](./scripts/theme_excluded_configs.txt).
Note that wildcards are supported.

## Demo content export

    # Set profile name.
    export CIVICTHEME_CONTENT_PROFILE=default

    # Prepare content with a clean installation.
    DREVOPS_DRUPAL_PROFILE=minimal CIVICTHEME_SKIP_SUBTHEME_ACTIVATION=1 CIVICTHEME_SKIP_GENERATED_CONTENT_CREATE=1 ahoy install-site

    # Export content.
    ahoy export-content

    # Export config.
    ahoy drush cde civictheme_content_${CIVICTHEME_CONTENT_PROFILE:-default}

## Updating fixture database files used for updates testing

1. Checkout this repository at the specific release
2. Update bare database dump:
   ```
   DREVOPS_DRUPAL_VERSION=10 DREVOPS_DRUPAL_PROFILE=minimal CIVICTHEME_SKIP_SUBTHEME_ACTIVATION=1 CIVICTHEME_SKIP_LIBRARY_INSTALL=1 SKIP_GENERATED_CONTENT_CREATE=1 ahoy build
   ahoy cli php docroot/core/scripts/dump-database-d8-mysql.php | gzip >  docroot/themes/contrib/civictheme/tests/fixtures/updates/drupal_<Drupal-Version>.minimal.civictheme_<CivicTheme-Version>.bare.php.gz
   ```
3. Update filled database dump:
   ```
   DREVOPS_DRUPAL_VERSION=10 DREVOPS_DRUPAL_PROFILE=minimal CIVICTHEME_SKIP_SUBTHEME_ACTIVATION=1 CIVICTHEME_SKIP_LIBRARY_INSTALL=1 ahoy build
   ahoy cli php docroot/core/scripts/dump-database-d8-mysql.php | gzip >  docroot/themes/contrib/civictheme/tests/fixtures/updates/drupal_<Drupal-Version>.minimal.civictheme_<CivicTheme-Version>.filled.php.gz
   ```
