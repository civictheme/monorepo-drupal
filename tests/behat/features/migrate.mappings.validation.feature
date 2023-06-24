@p1 @civictheme @civictheme_migrate @civictheme_migrate_validate
Feature: CivicTheme migrate validation

  Assert that validation of entities is enabled for migration mappings.
  This prevents using incorrect cross-bundle migrations.
  For example providing 'civictheme_image' bundle for 'civictheme_document'
  bundle migration should fail the migration of items with an incorrect bundle.

  Background:

    Given no managed files:
      | filename                                                 |
      | test_civictheme_migrate.media_civictheme_image_1.json    |
      | test_civictheme_migrate.media_civictheme_document_1.json |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                        | uri                                                                                |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://migration-source/test_civictheme_migrate.media_civictheme_image_1.json    |
      | migrate/civictheme_migrate.media_civictheme_document_1.json | public://migration-source/test_civictheme_migrate.media_civictheme_document_1.json |

    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_1.json
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_document_1.json
    And managed file:
      | path               | uri                                           |
      | migrate/dummy1.pdf | public://migration-source/migrated_dummy1.pdf |
      | migrate/dummy2.pdf | public://migration-source/migrated_dummy2.pdf |
      | migrate/dummy1.txt | public://migration-source/migrated_dummy1.txt |
      | migrate/dummy2.txt | public://migration-source/migrated_dummy2.txt |

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_icon source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_footer source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_primary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_secondary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"

  @api @drush
  Scenario: Migration of documents fails when images provided.
    Given I am logged in as a user with the "administrator" role

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_document_1.json" to "files[source_update_files][]"
    And I press "Update Migration"
    And I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I go to "admin/content/media"
    Then I should see "migrated_dummy1.pdf"
    And I should see "migrated_dummy2.pdf"
    And I should see "migrated_dummy1.txt"
    And I should see "migrated_dummy2.txt"
    And I should not see "migrated_dummy1.jpg"
    And I should not see "migrated_dummy2.jpg"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_document" migration map
    And I clear "media_civictheme_image" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_icon source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_footer source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_primary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_secondary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"
