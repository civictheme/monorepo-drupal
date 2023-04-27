@p1 @civictheme @civictheme_migrate @civictheme_migrate_promo
Feature: CivicTheme migrate module Promo Component mapping

  Background:
    Given no civictheme_page content:
      | title                                          |
      | [TEST] Migrated Content 5 with minimal mapping |

    And no blocks:
      | civictheme_demo_test_promo_block_1 |
      | civictheme_test_promo_block_1      |

    And no managed files:
      | filename                                                              |
      | test_civictheme_migrate.node_civictheme_page_6.json                   |
      | test_civictheme_migrate.media_civictheme_image_1.json                 |
      | test_civictheme_migrate.media_civictheme_image_2.json                 |
      | test_civictheme_migrate.media_civictheme_document_1.json              |
      | test_civictheme_migrate.civictheme_migrate.block_civictheme.json      |
      | test_civictheme_migrate.block_content_civictheme_component_block.json |

    And managed file:
      | path                                                                     | uri                                                                            |
      | migrate/civictheme_migrate.node_civictheme_page_6.json                   | public://test_civictheme_migrate.node_civictheme_page_6.json                   |
      | migrate/civictheme_migrate.media_civictheme_image_1.json                 | public://test_civictheme_migrate.media_civictheme_image_1.json                 |
      | migrate/civictheme_migrate.media_civictheme_image_2.json                 | public://test_civictheme_migrate.media_civictheme_image_2.json                 |
      | migrate/civictheme_migrate.media_civictheme_document_1.json              | public://test_civictheme_migrate.media_civictheme_document_1.json              |
      | migrate/civictheme_migrate.block_civictheme.json                         | public://test_civictheme_migrate.block_civictheme.json                         |
      | migrate/civictheme_migrate.block_content_civictheme_component_block.json | public://test_civictheme_migrate.block_content_civictheme_component_block.json |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_image" migration map
    And I clear "media_civictheme_document" migration map
    And I clear "node_civictheme_page" migration map
    And I clear "block_civictheme" migration map
    And I clear "block_content_civictheme_component_block" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.block_civictheme source.urls []"
    And I run drush "config-set migrate_plus.migration.block_content_civictheme_component_block source.urls []"

  @api @drush
  Scenario: Migration local sources can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    # Attaching 2 source image files as the node migration depends on images in both files.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_2.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_document_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_6.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/block_civictheme/edit"
    And I attach the file "migrate/civictheme_migrate.block_civictheme.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/block_content_civictheme_component_block/edit"
    And I attach the file "migrate/civictheme_migrate.block_content_civictheme_component_block.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Content 5 with minimal mapping"

    # Asserting field mappings.
    #Alias
    Then I should be in the "/migrated/page-content-51" path
    #Banner
    Then I should see "[TEST] Banner title - Migrated Page Content 51" in the ".ct-banner__title" element
    #Content
    And I should see an ".ct-basic-content" element
    And I should see the text "[TEST] Basic text content"
    #Promo
    And I should see an ".ct-promo.ct-theme-light" element
    And I should see an ".ct-promo.ct-theme-dark" element
    And I should see the text "[TEST] Promo 51"
    And I should see the text "[TEST] Promo 52"
    And I should see the link "[TEST] Promo link 1"
    And I should see the link "[TEST] Promo link 2"

    #promo block
    When I go to "admin/structure/block/block-content"
    Then I should see the text "[TEST] Component block"

    When I go to homepage
    Then I should see the text "[TEST] Promo Block 1"
    And I should see the link "[TEST] Promo Block link 1"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I clear "block_civictheme" migration map
    And I clear "block_content_civictheme_component_block" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.block_civictheme source.urls []"
    And I run drush "config-set migrate_plus.migration.block_content_civictheme_component_block source.urls []"
