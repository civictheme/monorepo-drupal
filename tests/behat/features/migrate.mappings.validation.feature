@p1 @civictheme @civictheme_migrate @civictheme_migrate_validate
Feature: CivicTheme migrate module

  These tests assert migration update functionality, migration mappings and
  schema sources.

  Background:
    Given no civictheme_page content:
      | title                     |
      | [TEST] Migrated Content 1 |

    And no managed files:
      | filename                                                 |
      | test_civictheme_migrate.media_civictheme_image_1.json    |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                        | uri                                                               |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://test_civictheme_migrate.media_civictheme_image_1.json    |

    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_1.json
    And managed file:
      | path               | uri                          |
      | migrate/dummy1.jpg | public://migrated_dummy1.jpg |
      | migrate/dummy2.jpg | public://migrated_dummy2.jpg |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_document" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"

  @api @drush @javascript
  Scenario: Migration local sources can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"
    And I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/execute"
    And I press "Execute"
    And I wait for the batch job to finish
    Then I should see the success message "2 failed"

    # Reset migration and configs.
    And I run drush "mr media_civictheme_document"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
