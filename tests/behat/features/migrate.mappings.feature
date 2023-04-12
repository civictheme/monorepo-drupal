@p1 @civictheme @civictheme_migrate
Feature: CivicTheme migrate module

  These tests assert migration update functionality, migration mappings and
  schema sources.

  # When _extending_ existing migrations, update fixture files and migration
  # result assertions below.
  #
  # When _adding_ new migrations, create new fixture files, add new tests
  # (copy from the existing tests below) and add migration result assertions.

  Background:
    Given no civictheme_page content:
      | title                     |
      | [TEST] Migrated Content 1 |
      | [TEST] Migrated Content 6 |

    And no managed files:
      | filename                                              |
      | test_civictheme_migrate.node_civictheme_page_2.json   |
      | test_civictheme_migrate.media_civictheme_image_1.json |
      | test_civictheme_migrate.media_civictheme_image_2.json |

    And managed file:
      | path                                             | filename                                              | uri                                                            |
      | civictheme_migrate.node_civictheme_page_2.json   | test_civictheme_migrate.node_civictheme_page_2.json   | public://test_civictheme_migrate.node_civictheme_page_2.json   |
      | civictheme_migrate.media_civictheme_image_1.json | test_civictheme_migrate.media_civictheme_image_1.json | public://test_civictheme_migrate.media_civictheme_image_1.json |
      | civictheme_migrate.media_civictheme_image_2.json | test_civictheme_migrate.media_civictheme_image_2.json | public://test_civictheme_migrate.media_civictheme_image_2.json |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
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

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "civictheme_migrate.node_civictheme_page_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    And I run drush "config-set civictheme_migrate.settings remote_authentication.type 'basic'"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.username 'civic'"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.password '2022civic'"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Content 1"

    # Asserting field mappings.
    # Alias.
    Then I should be in the "/test/migrated-content-1" path
    # Topics.
    And I should see "[TEST] Topic 1" in the ".ct-tag-list" element
    And I should see "[TEST] Topic 2" in the ".ct-tag-list" element
    And I should see "[TEST] Topic 3" in the ".ct-tag-list" element
    And I should see "[TEST] Topic 4" in the ".ct-tag-list" element
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
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.type ''"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.username ''"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.password ''"

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
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"

  @api @drush
  Scenario: Migration local sources with incorrect schema will trigger a validation error
    Given I am logged in as a user with the "administrator" role

    # Try uploading page sources for image migration.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "civictheme_migrate.node_civictheme_page_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    And I should see the text "error has been found:"
    And I should see the text "The specified file civictheme_migrate.node_civictheme_page_1.json could not be uploaded."
    And I should see the text "All array items must match schema"
    And I should see the text "The required properties (name) are missing"

    # Try uploading image sources for page migration.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    And I should see the text "error has been found:"
    And I should see the text "The specified file civictheme_migrate.media_civictheme_image_1.json could not be uploaded."
    And I should see the text "All array items must match schema"
    And I should see the text "The required properties (id) are missing"
