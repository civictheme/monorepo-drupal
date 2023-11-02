# CivicTheme Drupal module for GovCMS

----

Version: `{{ VERSION }}`

## How to use

1. Enable module
2. Run Drush `drush civictheme_govcms:remove-config` command to remove GovCMS
   configurations.

## Drush Commands

Remove all GovCMS configurations:

    drush civictheme_govcms:remove-config


Remove all GovCMS configurations, but preserve `media_types` and `content_types`:

    drush civictheme_govcms:remove-config --preserve=media_types,content_types

List of `--preserve` options:
  - `media_types`
  - `text_format`
  - `fields`
  - `content_types`
  - `vocabularies`
  - `user_roles`
  - `menus`
  - `pathauto_patterns`

## Other resources

- [CivicTheme Source site](https://github.com/salsadigitalauorg/civictheme_source)
- [CivicTheme CMS-agnostic library](https://github.com/salsadigitalauorg/civictheme_library)
- [CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme)
- [Default content for CivicTheme](https://github.com/salsadigitalauorg/civictheme_content)
