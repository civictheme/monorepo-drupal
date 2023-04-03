@p1 @civictheme @civictheme_migrate
Feature: Tests the CivicTheme migration functionality

  Background:
    Given managed file:
      | filename                          | uri                                      | path                             |
      | civictheme_migrate.page_1.json    | public://civictheme_migrate.page_1.json  | civictheme_migrate.page_1.json   |
      | civictheme_migrate.media_1.json   | public://civictheme_migrate.media_1.json | civictheme_migrate.media_1.json  |

  @api @javascript
  Scenario: Migration configuration form should be setup correctly
    Given I am logged in as a user with the "administrator" role
    When I go to "admin/config/civictheme-migrate"
    Then I should see the text "CivicTheme Migrate Settings"
    And I select the radio button "Local"
    And I should see the text "Upload extracted content JSON Files"
    And I should not see the text "Connect to remote API to retrieve extracted content JSON files"
    And I should not see a visible "textarea[name='remote[content_endpoint]'][required='required']" element
    And I should not see a visible "textarea[name='remote[media_endpoint]'][required='required']" element
    And the option "civictheme_page" from select "configuration[configuration_files][0][json_configuration_type]" is selected
    And I fill in "files[configuration_configuration_files_0_json_configuration_files]" with "sites/default/files/civictheme_migrate.page_1.json"
    And I press "Add one more"
    And I wait 3 seconds for AJAX to finish
    And the option "civictheme_media_image" from select "configuration[configuration_files][1][json_configuration_type]" is selected
    And I fill in "files[configuration_configuration_files_1_json_configuration_files]" with "sites/default/files/civictheme_migrate.media_1.json"
    And I should see the button "Save configuration"
    And I press the "Generate migration" button
    Then I should be in the "admin/structure/migrate/manage/civictheme_migrate/migrations" path
    And I should see the text "The configuration options have been saved."

    When I go to "/admin/structure/migrate"
    Then I should see the text "civictheme_migrate" in the "CivicTheme Migrate" row
    And I go to "admin/structure/migrate/manage/civictheme_migrate/migrations"
    And I should see the text "civictheme_page" in the "CivicTheme Page" row
    And I should see the text "civictheme_page_annotate" in the "CivicTheme Page Annotate" row
    And I should see the text "civictheme_media_image" in the "CivicTheme Media Image" row
    And I run drush "mr --group=civictheme_migrate"

  @api @javascript @check
  Scenario: Valid content from JSON can be imported and a Migration can be setup
    Given no page content:
      | title                          |
      | [TEST] Migrated Page Content 1 |
      | [TEST] Migrated Page Content 2 |

    When I am logged in as an administrator
    And I run drush "mim --group=civictheme_migrate"
    And I visit "/migrated/page-content-1"
    Then I should see "[TEST] Migrated Page Content 1" in the ".ct-banner__title" element
    And I should see an ".ct-basic-content" element
    And I should see an ".ct-list" element
    And I should see "[TEST] Promo card 1" in the ".ct-promo-card" element
    And I should see "[TEST] Event card 1" in the ".ct-promo-card" element
    And I should see "[TEST] Service card 1" in the ".ct-promo-card" element
    And I should see "[TEST] Subject card 1" in the ".ct-promo-card" element
    And I should see an ".ct-accordion" element

    And I visit "/migrated/page-content-2"
    Then I should see "[TEST] Migrated Page Content 2" in the ".ct-banner__title" element
    And I should see an ".ct-basic-content" element
    And I should see an ".ct-list" element
    And I should see "[TEST] Promo card 1" in the ".ct-promo-card" element
    And I should see "[TEST] Event card 1" in the ".ct-promo-card" element
    And I should see "[TEST] Service card 1" in the ".ct-promo-card" element
    And I should see "[TEST] Subject card 1" in the ".ct-promo-card" element
    And I should see an ".ct-accordion" element

    # Cleanup.
    When I run drush "mr --group=civictheme_migrate"
    And I run drush "cset civictheme_migrate.settings content_configuration_files [] -y"
    And I run drush "cset civictheme_migrate.settings media_configuration_files [] -y"
