@p0 @civictheme @civictheme_migrate @civictheme_migrate_menu
Feature: CivicTheme migrate module Component mapping

  Background:
    Given no "Primary navigation" menu_links:
      | Test primary 1 parent   |
      | Test primary 11 child   |
      | Test primary 12 child   |
      | Test primary 2          |
      | Test primary 3 external |

    And no "Secondary navigation" menu_links:
      | Test secondary 1 |
      | Test secondary 2 |

    And no "Footer" menu_links:
      | Test footer 1 |
      | Test footer 2 |

    And no managed files:
      | filename                                                                       |
      | test_civictheme_migrate.node_civictheme_page_5.json                            |
      | test_civictheme_migrate.menu_link_content_civictheme_primary_navigation.json   |
      | test_civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json |
      | test_civictheme_migrate.menu_link_content_civictheme_footer.json               |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                                              | uri                                                                                                      |
      | migrate/civictheme_migrate.node_civictheme_page_5.json                            | public://migration-source/test_civictheme_migrate.node_civictheme_page_5.json                            |
      | migrate/civictheme_migrate.menu_link_content_civictheme_primary_navigation.json   | public://migration-source/test_civictheme_migrate.menu_link_content_civictheme_primary_navigation.json   |
      | migrate/civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json | public://migration-source/test_civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json |
      | migrate/civictheme_migrate.menu_link_content_civictheme_footer.json               | public://migration-source/test_civictheme_migrate.menu_link_content_civictheme_footer.json               |

    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.node_civictheme_page_5.json
    # @see fixtures/migrate/civictheme_migrate.menu_link_content_civictheme_primary_navigation.json
    # @see fixtures/migrate/civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json
    # @see fixtures/migrate/civictheme_migrate.menu_link_content_civictheme_footer.json

    # Fully reset migration runs and migration configs.
    And I clear "node_civictheme_page" migration map
    And I clear "menu_link_content_civictheme_primary_navigation" migration map
    And I clear "menu_link_content_civictheme_secondary_navigation" migration map
    And I clear "menu_link_content_civictheme_footer" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_icon source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_footer source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_primary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_secondary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page_annotate source.urls []"

  @api @drush
  Scenario: Migration local sources for menus can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_5.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/menu_link_content_civictheme_primary_navigation/edit"
    And I attach the file "migrate/civictheme_migrate.menu_link_content_civictheme_primary_navigation.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/menu_link_content_civictheme_secondary_navigation/edit"
    And I attach the file "migrate/civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/menu_link_content_civictheme_footer/edit"
    And I attach the file "migrate/civictheme_migrate.menu_link_content_civictheme_footer.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"

    # Primary navigation items.
    Then I go to "admin/structure/menu/manage/civictheme-primary-navigation"
    And I should see the link "Test primary 1 parent"
    And I should see the link "Test primary 11 child"
    And I should see the link "Test primary 12 child"
    And I should see the link "Test primary 2"
    And I should see the link "Test primary 3 external"
    # Assert that link was migrated as external.
    When I go to homepage
    Then the ".ct-primary-navigation .ct-link--external" element should contain "Test primary 3 external"

    # Secondary navigation items.
    Then I go to "admin/structure/menu/manage/civictheme-secondary-navigation"
    And I should see the link "Test secondary 1"
    And I should see the link "Test secondary 2"

    # Footer items.
    Then I go to "admin/structure/menu/manage/civictheme-footer"
    And I should see the link "Test footer 1"
    And I should see the link "Test footer 2"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "node_civictheme_page" migration map
    And I clear "menu_link_content_civictheme_primary_navigation" migration map
    And I clear "menu_link_content_civictheme_secondary_navigation" migration map
    And I clear "menu_link_content_civictheme_footer" migration map
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_secondary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_primary_navigation source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_link_content_civictheme_footer source.urls []"
    And no managed files:
      | filename                                                                       |
      | test_civictheme_migrate.node_civictheme_page_5.json                            |
      | test_civictheme_migrate.menu_link_content_civictheme_primary_navigation.json   |
      | test_civictheme_migrate.menu_link_content_civictheme_secondary_navigation.json |
      | test_civictheme_migrate.menu_link_content_civictheme_footer.json               |

  @api @drush
  Scenario Outline: Migration local sources with incorrect schema will trigger a validation error
    Given I am logged in as a user with the "administrator" role

    When I go to "<path>"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_5.json" to "files[source_update_files][]"
    And I press "Update Migration"

    And I should see the text "error has been found:"
    And I should see the text "The specified file civictheme_migrate.node_civictheme_page_5.json could not be uploaded."
    And I should see the text "The data (array) must match the type: object"

    Examples:
      | path                                                                                                                |
      | admin/structure/migrate/manage/civictheme_migrate/migrations/menu_link_content_civictheme_primary_navigation/edit   |
      | admin/structure/migrate/manage/civictheme_migrate/migrations/menu_link_content_civictheme_secondary_navigation/edit |
      | admin/structure/migrate/manage/civictheme_migrate/migrations/menu_link_content_civictheme_footer/edit               |
