@p1 @civictheme @civictheme_migrate @civictheme_migrate_reference
Feature: CivicTheme migrate module Component references

  Background:
    Given no civictheme_page content:
      | title                           |
      | [TEST] Migrated Page Content 71 |
      | [TEST] Migrated Page Content 72 |

    And no managed files:
      | filename                                                 |
      | test_civictheme_migrate.node_civictheme_page_7.json      |
      | test_civictheme_migrate.media_civictheme_image_1.json    |
      | test_civictheme_migrate.media_civictheme_image_2.json    |
      | test_civictheme_migrate.media_civictheme_document_1.json |

    And managed file:
      | path                                                        | uri                                                               |
      | migrate/civictheme_migrate.node_civictheme_page_7.json      | public://test_civictheme_migrate.node_civictheme_page_7.json      |
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
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"

  @api @drush
  Scenario: Links and references are replaced during migrations
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
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_7.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page_annotate/edit"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_7.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Page Content 71"

    # Asserting field mappings.
    #Alias
    Then I should be in the "/migrated/page-content-71" path
    #Banner
    Then I should see "[TEST] Banner title - Migrated Page Content 71" in the ".ct-banner__title" element
    #Content
    And I should see an ".ct-basic-content" element

    And I visit "civictheme_page" "[TEST] Migrated Page Content 72"
    # Asserting field mappings.
    #Alias
    Then I should be in the "/migrated/page-content-72" path
    #Banner
    Then I should see "[TEST] Banner title - Migrated Page Content 72" in the ".ct-banner__title" element
    #Content
    And I should see an ".ct-basic-content" element
    And I should see the text "[TEST] Basic text content"
    #attachment
    And I should see an ".ct-figure" element
    And the response should contain "dummy1.pdf"
    And the response should contain "448f7b0e-19a3-4c43-b1d7-5b1f196dbf98"
    And the response should contain "dummy1.jpg"
    And the response should contain "c97a9b08-f3b0-477b-97f7-8b61f9d4a527"
    And the response should contain "dummy2.jpg"
    And the response should contain "7fdce6fd-3bcb-4ffa-b349-2a6eb0b049c4"

    # New assertions for links conversion
    # Existing referenced content should be converted to Linkit media items
    And the response should contain "<a href=\"media:"
    # Non-existing referenced content should stay as is (relative or absolute links)
    And the response should contain "<a href=\"http://"
    And the response should contain "<a href=\"/"
    And the response should not contain "<a href=\"media:" with count "2"

    # New assertions for embedded images
    # Links to existing images should be asserted for
    And the response should contain "<img src=\"http://"
    # Links to non-existing images should be asserted for AND the presence of the created images
    And the response should contain "<img src=\"public://migrated_dummy"
    And the response should not contain "<img src=\"http://" with count "2"
    # Referencing of newly created images should not create duplicates
    And the response should not contain "<img src=\"public://" with count "2"

    # New assertions for embedded documents
    # Links to existing documents should be asserted for
    And the response should contain "<a href=\"http://"
    # Links to non-existing documents should be asserted for AND the presence of the created documents
    And the response should contain "<a href=\"public://migrated_dummy"
    And the response should not contain "<a href=\"http://" with count "2"
    # Referencing of newly created documents should not create duplicates
    And the response should not contain "<a href=\"public://" with count "2"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I clear "node_civictheme_page_annotate" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"
