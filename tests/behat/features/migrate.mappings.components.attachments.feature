@p1 @civictheme @civictheme_migrate @civictheme_migrate_attachment
Feature: CivicTheme migrate module Component mapping

  Background:
    Given no civictheme_page content:
      | title                           |
      | [TEST] Migrated Page Content 1  |

    And no managed files:
      | filename                                                  |
      | test_civictheme_migrate.node_civictheme_page_4.json       |
      | test_civictheme_migrate.media_civictheme_image_1.json     |
      | test_civictheme_migrate.media_civictheme_image_2.json     |
      | test_civictheme_migrate.media_civictheme_document_1.json  |

    And managed file:
      | path                                                | filename                                                     | uri                                                                |
      | civictheme_migrate.node_civictheme_page_4.json      | test_civictheme_migrate.node_civictheme_page_4.json          | public://test_civictheme_migrate.node_civictheme_page_4.json       |
      | civictheme_migrate.media_civictheme_image_1.json    | test_civictheme_migrate.media_civictheme_image_1.json        | public://test_civictheme_migrate.media_civictheme_image_1.json     |
      | civictheme_migrate.media_civictheme_image_2.json    | test_civictheme_migrate.media_civictheme_image_2.json        | public://test_civictheme_migrate.media_civictheme_image_2.json     |
      | civictheme_migrate.media_civictheme_document_1.json | test_civictheme_migrate.media_civictheme_document_1.json     | public://test_civictheme_migrate.media_civictheme_document_1.json  |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_image" migration map
    And I clear "media_civictheme_document" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"

  @api @drush
  Scenario: Migration local sources can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    # Attaching 2 source image files as the node migration depends on images in both files.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "civictheme_migrate.media_civictheme_image_2.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I attach the file "civictheme_migrate.media_civictheme_document_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "civictheme_migrate.node_civictheme_page_4.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Page Content 1"

    # Asserting field mappings.
    #Alias
    Then I should be in the "/migrated/page-content-1" path
    #Banner
    Then I should see "[TEST] Banner title - Migrated Page Content 1" in the ".ct-banner__title" element
    #Content
    And I should see an ".ct-basic-content" element
    And I should see the text "[TEST] Basic text content"
    #attachment
    And I should see an ".ct-attachment" element
    And I should see an ".ct-attachment__content" element
    And I should see the text "[TEST] Attachments"
    And I should see 3 ".ct-attachment__links .ct-item-list__item" elements

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
