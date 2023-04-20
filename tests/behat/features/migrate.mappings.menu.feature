@p0 @civictheme @civictheme_migrate @civictheme_migrate_menu
Feature: CivicTheme migrate module Component mapping

  Background:
    Given no managed files:
      | filename                                                |
      | test_civictheme_migrate.menu_civictheme_primary.json    |
      | test_civictheme_migrate.menu_civictheme_secondary.json  |
      | test_civictheme_migrate.menu_civictheme_footer.json     |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                        | uri                                                              |
      | migrate/civictheme_migrate.menu_civictheme_primary.json     | public://test_civictheme_migrate.menu_civictheme_primary.json    |
      | migrate/civictheme_migrate.menu_civictheme_secondary.json   | public://test_civictheme_migrate.menu_civictheme_secondary.json  |
      | migrate/civictheme_migrate.menu_civictheme_footer.json      | public://test_civictheme_migrate.menu_civictheme_footer.json     |



    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.menu_civictheme_primary.json
    # @see fixtures/migrate/civictheme_migrate.menu_civictheme_secondary.json
    # @see fixtures/migrate/civictheme_migrate.menu_civictheme_footer.json

    # Fully reset migration runs and migration configs.
    And I clear "menu_civictheme_primary" migration map
    And I clear "menu_civictheme_secondary" migration map
    And I clear "menu_civictheme_footer" migration map
    And I run drush "config-set migrate_plus.migration.menu_civictheme_primary source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_civictheme_secondary source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_civictheme_footer source.urls []"

  @api @drush
  Scenario: Migration local sources for components can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    # Attaching 2 source image files as the node migration depends on images in both files.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/menu_civictheme_primary/edit"
    And I attach the file "migrate/civictheme_migrate.menu_civictheme_primary.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/menu_civictheme_secondary/edit"
    And I attach the file "migrate/civictheme_migrate.menu_civictheme_secondary.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/menu_civictheme_footer/edit"
    And I attach the file "migrate/civictheme_migrate.menu_civictheme_footer.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"

    # Primary menu items.
    Then I go to "admin/structure/menu/manage/civictheme-primary-navigation"
    And I should see the link "Theme 1"
    And I should see the link "Theme 2"
    And I should see the link "Theme 3"
    And I should see the link "For businesses"
    And I should see the link "For individuals"
    And I should see the link "For government"

    # Secondary menu items.
    Then I go to "admin/structure/menu/manage/civictheme-secondary-navigation"
    And I should see the link "Secondary 1"
    And I should see the link "Secondary 2"

    # Footer menu items.
    Then I go to "admin/structure/menu/manage/civictheme-footer"
    And I should see the link "Footer 1"
    And I should see the link "Footer 2"

    # All the menu items are visible on FE.
    When I go to the homepage

    # Primary menu.
    And I should see the link "Theme 1"
    And I should see the link "Theme 2"
    And I should see the link "Theme 3"
    And I should see the link "For businesses"
    And I should see the link "For individuals"
    And I should see the link "For government"

    # Secondary menu.
    And I should see the link "Secondary 1"
    And I should see the link "Secondary 2"

    # Footer menu.
    And I should see the link "Footer 1"
    And I should see the link "Footer 2"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.menu_civictheme_primary source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_civictheme_secondary source.urls []"
    And I run drush "config-set migrate_plus.migration.menu_civictheme_footer source.urls []"
    And no managed files:
      | filename                                                |
      | test_civictheme_migrate.menu_civictheme_primary.json    |
      | test_civictheme_migrate.menu_civictheme_secondary.json  |
      | test_civictheme_migrate.menu_civictheme_footer.json     |
