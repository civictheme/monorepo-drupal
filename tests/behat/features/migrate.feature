@p1 @civictheme @civictheme_migrate
Feature: Tests the CivicTheme migration functionality

  @api
  Scenario Outline: Only administrator can access the CivicTheme migration configuration form

    Given I am logged in as a user with the "<role>" role
    When I go to "admin/config/civictheme-migrate"
    Then the response status code should be <code>

    Examples:
      | role                          | code |
      | civictheme_content_author     | 403  |
      | civictheme_content_approver   | 403  |
      | civictheme_site_administrator | 403  |
      | administrator                 | 200  |

  @api @javascript
  Scenario: Migration configuration form should be setup correctly
    Given I am logged in as a user with the "administrator" role
    When I go to "admin/config/civictheme-migrate"
    Then I should see the text "CivicTheme Migrate Settings"
    And I select the radio button "Local"
    And I should see the text "Upload extracted content JSON Files"
    And I should not see the text "Connect to remote API to retrieve extracted content JSON files"
    And I should not see a visible "textarea[name='content_endpoint'][required='required']" element
    And I should not see a visible "textarea[name='media_endpoint'][required='required']" element
    And I select the radio button "Remote"
    And I should see the text "Connect to remote API to retrieve extracted content JSON files"
    And I see the text "Migration source page content JSON URL endpoints"
    And I see the text "Migration source media JSON URL endpoints"
    And I should see a visible "textarea[name='content_endpoint'][required='required']" element
    And I should see a visible "textarea[name='media_endpoint'][required='required']" element
    And I should not see a visible "input[name='auth_username'][required='required']" element
    And I should not see a visible "input[name='auth_password'][required='required']" element
    And I select the radio button "Basic authentication"
    And I should see a visible "textarea[name='content_endpoint'][required='required']" element
    And I should see a visible "textarea[name='media_endpoint'][required='required']" element
    And I should see a visible "input[name='auth_username'][required='required']" element
    And I should see a visible "input[name='auth_password'][required='required']" element
    And I should see the button "Retrieve files"
    And I should see the button "Save configuration"

  @api @javascript
  Scenario: Valid Extracted content JSON can be retrieved and retriever handles incorrect URLs
    Given managed file:
      | filename                               | uri                                             | path                                   |
      | civictheme_migrate.invalid_json_1.json | public://civictheme_migrate.invalid_json_1.json | civictheme_migrate.invalid_json_1.json |
    Given I am logged in as a user with the "administrator" role
    When I go to "admin/config/civictheme-migrate"
    And I select the radio button "Remote"
    And I fill in "Migration source page content JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.invalid_json_1.json"
    And I press the "Retrieve files" button
    # Can't test error messages directly as admin theme is not adding the behat configured error class.
    And I should see the text "JSON is malformed / invalid"
    And I fill in "Migration source media JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.invalid_json_1.json"
    And I press the "Retrieve files" button
    # Can't test error messages directly as admin theme is not adding the behat configured error class.
    And I should see the text "JSON is malformed / invalid"
    And I fill in "Migration source page content JSON URL endpoints" with "http://nginx:8080/sites/default/files/file-does-not-exist.json"
    And I press the "Retrieve files" button
    And I should see the message containing "Client error"

  @api @javascript
  Scenario: Valid Extracted content JSON can be imported and a Migration can be setup
    Given managed file:
      | filename                                | uri                                              | path                                    |
      | civictheme_migrate.page_content_1.json  | public://civictheme_migrate.page_content_1.json  | civictheme_migrate.page_content_1.json  |
      | civictheme_migrate.page_content_2.json  | public://civictheme_migrate.page_content_2.json  | civictheme_migrate.page_content_2.json  |
      | civictheme_migrate.media_content_1.json | public://civictheme_migrate.media_content_1.json | civictheme_migrate.media_content_1.json |
      | civictheme_migrate.media_content_2.json | public://civictheme_migrate.media_content_2.json | civictheme_migrate.media_content_2.json |
    Given I am logged in as an administrator
    When I go to "admin/config/civictheme-migrate"
    And I select the radio button "Remote"
    And I fill in "Migration source page content JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.page_content_1.json"
    And I fill in "Migration source media JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.media_content_1.json"
    And I press the "Retrieve files" button
    And I fill in "Migration source page content JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.page_content_2.json"
    And I fill in "Migration source media JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.media_content_2.json"
    And I press the "Retrieve files" button
    And I should see the message "Migration content files have been retrieved"
    And I press the "Generate migration" button
    And I should be in the "admin/structure/migrate/manage/civictheme_migrate/migrations" path
    And I run drush "mim --group=civictheme_migrate"

    And I visit "/test/migrated-content-1"
    And I should see "[TEST] Banner title - Migrated Content 1" in the ".ct-banner__title h1" element
    And I should see "Last updated: 8 Oct 2022"
    And I should see an ".ct-layout.ct-vertical-spacing--both" element
    And I should see an ".ct-banner.ct-theme-dark.ct-banner--decorative" element
    And I should see an ".ct-banner__wrapper__inner.ct-background--darken" element
    And I run drush "mr --group=civictheme_migrate"
    And I run drush "cset civictheme_migrate.settings content_configuration_files [] -y"
    And I run drush "cset civictheme_migrate.settings media_configuration_files [] -y"
