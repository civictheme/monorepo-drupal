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
    Given no civictheme_page content:
      | title                     |
      | [TEST] Migrated Content 1 |
      | [TEST] Migrated Content 2 |

    And no managed files:
      | filename                                                 |
      | test_civictheme_migrate.node_civictheme_page_1.json      |
      | test_civictheme_migrate.node_civictheme_page_2.json      |
      | test_civictheme_migrate.node_civictheme_page_3.json      |
      | test_civictheme_migrate.media_civictheme_image_1.json    |
      | test_civictheme_migrate.media_civictheme_image_2.json    |
      | test_civictheme_migrate.media_civictheme_document_1.json |

    And no topic terms:
      | name                          |
      | [TEST] Migrated event topic 1 |
      | [TEST] Migrated topic 1       |
      | [TEST] Migrated topic 2       |
      | [TEST] Migrated topic 3       |
      | [TEST] Migrated topic 4       |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                        | uri                                                               |
      | migrate/civictheme_migrate.node_civictheme_page_2.json      | public://test_civictheme_migrate.node_civictheme_page_2.json      |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://test_civictheme_migrate.media_civictheme_image_1.json    |
      | migrate/civictheme_migrate.media_civictheme_image_2.json    | public://test_civictheme_migrate.media_civictheme_image_2.json    |
      | migrate/civictheme_migrate.media_civictheme_document_1.json | public://test_civictheme_migrate.media_civictheme_document_1.json |

    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_1.json
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_2.json
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_document_1.json
    And managed file:
      | path               | uri                      |
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
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"

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
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Content 1"

    # Asserting field mappings.
    # Alias.
    Then I should be in the "/test/migrated-content-1" path
    # Topics.
    And I should see "[TEST] Migrated topic 1" in the ".ct-tag-list" element
    And I should see "[TEST] Migrated topic 2" in the ".ct-tag-list" element
    And I should see "[TEST] Migrated topic 3" in the ".ct-tag-list" element
    And I should see "[TEST] Migrated topic 4" in the ".ct-tag-list" element
    # Vertical spacing.
    And I should see a ".ct-layout.ct-vertical-spacing--top" element
    # Hide sidebar.
    And I should not see a ".ct-layout__sidebar" element
    # Show last updated date and last updated date.
    And I should see "Last updated: 8 Oct 2022"
    # Show TOC.
    And I should see a ".table-of-contents-container" element
    # Banner theme.
    And I should see a ".ct-banner.ct-theme-dark" element
    # Banner title override.
    And I should see "[TEST] Banner title - Migrated Content 1" in the ".ct-banner .ct-banner__title" element
    # Banner type.
    And I should see a ".ct-banner.ct-banner--decorative" element
    # Banner blend mode.
    And I should see a ".ct-banner .ct-background--darken" element
    # Banner featured image.
    And I should see a ".ct-banner .ct-banner__featured-image" element
    And the response should contain "dummy2.jpg"
    And the response should contain "Dummy file 2"
    # Banner background.
    And the response should contain "dummy3.jpg"
    # Hide breadcrumb.
    And I should not see a ".ct-banner .ct-breadcrumb" element

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"

  @api @drush
  Scenario: Migration remote sources can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    # Attaching 2 source image files as the node migration depends on images in both files.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I fill in "Source as URLs" with:
    """
    http://nginx:8080/sites/default/files/test_civictheme_migrate.media_civictheme_image_1.json
    http://nginx:8080/sites/default/files/test_civictheme_migrate.media_civictheme_image_2.json
    """
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I fill in "Source as URLs" with "http://nginx:8080/sites/default/files/test_civictheme_migrate.media_civictheme_document_1.json"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I fill in "Source as URLs" with "http://nginx:8080/sites/default/files/test_civictheme_migrate.node_civictheme_page_2.json"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Content 2 with minimal mapping"

    # No need to assert for field mappings and maintain it in this test as the
    # mappings were tested in the test above.
    # Alias.
    Then the response should contain "200"
    And I should see "[TEST] Migrated Content 2 with minimal mapping" in the ".ct-banner .ct-banner__title" element

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"

  @api @drush
  Scenario: Migration local sources with incorrect schema will trigger a validation error
    Given I am logged in as a user with the "administrator" role

    # Try uploading page sources for image migration.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    And I should see the text "error has been found:"
    And I should see the text "The specified file civictheme_migrate.node_civictheme_page_1.json could not be uploaded."
    And I should see the text "All array items must match schema"
    And I should see the text "The required properties (name) are missing"

    # Try uploading image sources for page migration.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    And I should see the text "error has been found:"
    And I should see the text "The specified file civictheme_migrate.media_civictheme_image_1.json could not be uploaded."
    And I should see the text "All array items must match schema"
    And I should see the text "The required properties (id) are missing"
