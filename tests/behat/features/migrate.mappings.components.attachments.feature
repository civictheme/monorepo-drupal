@p1 @civictheme @civictheme_migrate @civictheme_migrate_attachment
Feature: CivicTheme migrate module Component mapping

  Background:
    Given no civictheme_page content:
      | title                           |
      | [TEST] Migrated Page Content 41 |

    And no managed files:
      | filename                                                 |
      | test_civictheme_migrate.node_civictheme_page_4.json      |
      | test_civictheme_migrate.media_civictheme_image_1.json    |
      | test_civictheme_migrate.media_civictheme_image_2.json    |
      | test_civictheme_migrate.media_civictheme_document_1.json |

    And managed file:
      | path                                                        | uri                                                               |
      | migrate/civictheme_migrate.node_civictheme_page_4.json      | public://test_civictheme_migrate.node_civictheme_page_4.json      |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://test_civictheme_migrate.media_civictheme_image_1.json    |
      | migrate/civictheme_migrate.media_civictheme_image_2.json    | public://test_civictheme_migrate.media_civictheme_image_2.json    |
      | migrate/civictheme_migrate.media_civictheme_document_1.json | public://test_civictheme_migrate.media_civictheme_document_1.json |

    And managed file:
      | path               | uri                          |
      | migrate/dummy1.jpg | public://migrated_dummy1.jpg |
      | migrate/dummy2.jpg | public://migrated_dummy2.jpg |
      | migrate/dummy3.jpg | public://migrated_dummy3.jpg |
      | migrate/dummy4.jpg | public://migrated_dummy4.jpg |
      | migrate/dummy1.pdf | public://migrated_dummy1.pdf |
      | migrate/dummy2.pdf | public://migrated_dummy2.pdf |
      | migrate/dummy3.pdf | public://migrated_dummy3.pdf |
      | migrate/dummy4.pdf | public://migrated_dummy4.pdf |
      | migrate/dummy1.txt | public://migrated_dummy1.txt |
      | migrate/dummy2.txt | public://migrated_dummy2.txt |
      | migrate/dummy3.txt | public://migrated_dummy3.txt |
      | migrate/dummy4.txt | public://migrated_dummy4.txt |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_image" migration map
    And I clear "media_civictheme_document" migration map
    And I clear "node_civictheme_page" migration map
    And I clear "node_civictheme_page_annotate" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_icon source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_footer source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_primary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_secondary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"

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
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_4.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page_annotate/edit"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_4.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Page Content 41"

    # Asserting field mappings.
    #Alias
    Then I should be in the "/migrated/page-content-41" path
    #Banner
    Then I should see "[TEST] Banner title - Migrated Page Content 41" in the ".ct-banner__title" element
    #Content
    And I should see an ".ct-basic-content" element
    And I should see the text "[TEST] Basic text content"
    #attachment
    And I should see an ".ct-attachment" element
    And I should see an ".ct-attachment__content" element
    And I should see the text "[TEST] Attachments"
    And I should see 3 ".ct-attachment__links .ct-item-list__item" elements
    And the response should contain "dummy1.pdf"
    And the response should contain "dummy2.pdf"
    And the response should contain "dummy1.txt"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I clear "node_civictheme_page_annotate" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_icon source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_footer source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_primary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_secondary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"
