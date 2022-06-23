# CivicTheme Drupal module for GovCMS

----

Version: `{{ VERSION }}`

## Other resources

- [CivicTheme Source site](https://github.com/salsadigitalauorg/civictheme_source)
- [CivicTheme CMS-agnostic library](https://github.com/salsadigitalauorg/civictheme_library)
- [CivicTheme Drupal theme](https://github.com/salsadigitalauorg/civictheme)
- [Default content for CivicTheme](https://github.com/salsadigitalauorg/civictheme_content)

## Drush Commands

Remove all Govcms configurations
```
drush civictheme_govcms:remove-config
```

Remove all Govcms configurations - Preserve [media_types, content_types]
```
drush civictheme_govcms:remove-config --preserve=media_types,content_types
```

* list of allowed --preserve options
  - media_types
  - text_format
  - fields
  - content_types
  - vocabularies
  - user_roles
  - menus
  - pathauto_patterns