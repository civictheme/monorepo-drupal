@p1 @civictheme @civictheme_migrate
Feature: CivicTheme migrate module

  These tests assert migration update functionality, migration mappings and
  schema sources.

  # When _extending_ existing migrations, update fixture files and migration
  # result assertions below.
  #
  # When _adding_ new migrations, create new fixture files, add new tests
  # (copy from the existing tests below) and add migration result assertions.
  #
  # Use "test_" or "[TEST]" prefix for content created within test.
  # Use "migrated_" or "[TEST] Migrated" prefix for content referenced within
  # migration sources.

  Background:
    Given no managed files:
      | filename                                                 |
      | test_civictheme_migrate.media_civictheme_image_1.json    |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                        | uri                                                                                |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://migration-source/test_civictheme_migrate.media_civictheme_image_1.json    |

    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_1.json
    And managed file:
      | path               | uri                                           |
      | migrate/dummy1.jpg | public://migration-source/migrated_dummy1.jpg |
      | migrate/dummy2.jpg | public://migration-source/migrated_dummy2.jpg |
      | migrate/dummy3.jpg | public://migration-source/migrated_dummy3.jpg |
      | migrate/dummy4.jpg | public://migration-source/migrated_dummy4.jpg |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_image" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"

  @api @drush
  Scenario: Migration Media should import alt text with limit
    Given I am logged in as a user with the "administrator" role

    # Attaching 2 source image files as the node migration depends on images in both files.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I go to "admin/content/media"
    And I should see "migrated_dummy2.jpg"
    And I click "migrated_dummy2.jpg"
    Then the response status code should be 200
    And the response should contain "[TEST CHAR 510]"
    And the response should not contain "[TEST 512 character]"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
