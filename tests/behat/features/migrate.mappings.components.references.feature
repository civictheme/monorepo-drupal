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
      | path                                                        | uri                                                                                |
      | migrate/civictheme_migrate.node_civictheme_page_7.json      | public://migration-source/test_civictheme_migrate.node_civictheme_page_7.json      |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://migration-source/test_civictheme_migrate.media_civictheme_image_1.json    |
      | migrate/civictheme_migrate.media_civictheme_image_2.json    | public://migration-source/test_civictheme_migrate.media_civictheme_image_2.json    |
      | migrate/civictheme_migrate.media_civictheme_document_1.json | public://migration-source/test_civictheme_migrate.media_civictheme_document_1.json |

    And managed file:
      | path               | uri                                           |
      | migrate/dummy1.jpg | public://migration-source/migrated_dummy1.jpg |
      | migrate/dummy2.jpg | public://migration-source/migrated_dummy2.jpg |
      | migrate/dummy3.jpg | public://migration-source/migrated_dummy3.jpg |
      | migrate/dummy4.jpg | public://migration-source/migrated_dummy4.jpg |
      | migrate/dummy1.pdf | public://migration-source/migrated_dummy1.pdf |
      | migrate/dummy2.pdf | public://migration-source/migrated_dummy2.pdf |
      | migrate/dummy3.pdf | public://migration-source/migrated_dummy3.pdf |
      | migrate/dummy4.pdf | public://migration-source/migrated_dummy4.pdf |
      | migrate/dummy1.txt | public://migration-source/migrated_dummy1.txt |
      | migrate/dummy2.txt | public://migration-source/migrated_dummy2.txt |
      | migrate/dummy3.txt | public://migration-source/migrated_dummy3.txt |
      | migrate/dummy4.txt | public://migration-source/migrated_dummy4.txt |

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

    And I run drush "config-set civictheme_migrate.settings local_domains --input-format=yaml [http://nginx:8080]"

    And I run drush "mim node_civictheme_page_annotate --update --execute-dependencies"

    When I visit "civictheme_page" "[TEST] Migrated Page Content 71"
    Then I should be in the "/migrated/page-content-71" path
    And I should see "[TEST] Banner title - Migrated Page Content 71" in the ".ct-banner__title" element

    # Asserting for correct assets processing.
    And I visit "civictheme_page" "[TEST] Migrated Page Content 72"
    Then I should be in the "/migrated/page-content-72" path
    And I should see "[TEST] Banner title - Migrated Page Content 72" in the ".ct-banner__title" element

    And I should see the text "[TEST] Basic text content 721"
    # Link to existing document.
    And I should see "link to existing document" in the ".ct-basic-content a[href$='migrated_dummy1.pdf']" element
    And I should see "link to existing document" in the ".ct-basic-content a[href$='migrated_dummy1.pdf'][data-entity-uuid]" element
    And I should see the ".ct-basic-content a[href$='migrated_dummy1.pdf']" element with the "data-entity-type" attribute set to "media"
    And I should see the ".ct-basic-content a[href$='migrated_dummy1.pdf']" element with the "data-entity-substitution" attribute set to "media"
    # Link to existing content.
    And I should see "link to existing page" in the ".ct-basic-content a[href='/migrated/page-content-71']" element
    And I should see "link to existing page" in the ".ct-basic-content a[href='/migrated/page-content-71'][data-entity-uuid]" element
    And I should see the ".ct-basic-content a[href='/migrated/page-content-71']" element with the "data-entity-type" attribute set to "node"
    And I should see the ".ct-basic-content a[href='/migrated/page-content-71']" element with the "data-entity-substitution" attribute set to "canonical"
    And I should see the ".ct-basic-content a[href='/migrated/page-content-71']" element with the "title" attribute set to "[TEST] Migrated Page Content 71"
    # Link to existing media image.
    And I should see the ".ct-basic-content img[src$='files/migrated_dummy1.jpg']" element with the "alt" attribute set to "Existing image alt"
    # Absolute link.
    And I should see an ".ct-basic-content a[href='https://example.com'].ct-content-link" element

    And I should see the text "[TEST] Basic text content 722"
    # Link to existing document.
    And I should see "link to existing external document set as local" in the ".ct-basic-content a[href$='migrated_dummy2.pdf']" element
    And I should see "link to existing external document set as local" in the ".ct-basic-content a[href$='migrated_dummy2.pdf'][data-entity-uuid]" element
    And I should see the ".ct-basic-content a[href$='migrated_dummy2.pdf']" element with the "data-entity-type" attribute set to "media"
    And I should see the ".ct-basic-content a[href$='migrated_dummy2.pdf']" element with the "data-entity-substitution" attribute set to "media"
    # Link to existing content.
    And I should see "link to existing page" in the ".ct-basic-content a[href='/migrated/page-content-71']" element
    And I should see "link to existing page" in the ".ct-basic-content a[href='/migrated/page-content-71'][data-entity-uuid]" element
    And I should see the ".ct-basic-content a[href='/migrated/page-content-71']" element with the "data-entity-type" attribute set to "node"
    And I should see the ".ct-basic-content a[href='/migrated/page-content-71']" element with the "data-entity-substitution" attribute set to "canonical"
    And I should see the ".ct-basic-content a[href='/migrated/page-content-71']" element with the "title" attribute set to "[TEST] Migrated Page Content 71"
    # Link to existing media image.
    And I should see the ".ct-basic-content img[src$='files/migrated_dummy2.jpg']" element with the "alt" attribute set to "Existing external image set as local alt"
    # Absolute link without entity reference would not be converted.
    And I should see an ".ct-basic-content a[href='http://nginx:8080/'].ct-content-link" element

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
