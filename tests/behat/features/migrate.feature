@civictheme @civictheme_migrate
Feature: Tests the CivicTheme migration functionality

  @api @javascript
  Scenario: Migration configuration form should be setup correctly
    Given I am logged in as a user with the "administrator" role
    When I go to "admin/config/civictheme-migrate"
    Then I should see the text "CivicTheme Migrate Settings"
    And I select the radio button "Local"
    And I should see the text "Upload Merlin extracted content JSON Files"
    And I should not see the text "Connect to remote Merlin UI API to retrieve extracted content JSON files"
    And I should not see a visible "textarea[name='endpoint'][required='required']" element
    And I select the radio button "Remote"
    And I should see the text "Connect to remote Merlin UI API to retrieve extracted content JSON files"
    And I see the text "Merlin extracted content JSON URL endpoints"
    And I should see a visible "textarea[name='endpoint'][required='required']" element
    And I should not see a visible "input[name='auth_username'][required='required']" element
    And I should not see a visible "input[name='auth_password'][required='required']" element
    And I select the radio button "Basic authentication"
    And I should see a visible "textarea[name='endpoint'][required='required']" element
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
    And I fill in "Merlin extracted content JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.invalid_json_1.json"
    And I press the "Retrieve files" button
    # Can't test error messages directly as admin theme is not adding the behat configured error class.
    And I should see the text "is not valid JSON"
    And I fill in "Merlin extracted content JSON URL endpoints" with "http://nginx:8080/sites/default/files/file-does-not-exist.json"
    And I press the "Retrieve files" button
    And I should see the message containing "Client error"

  @api @javascript
  Scenario: Valid Extracted content JSON can be imported and a Migration can be setup
    Given managed file:
      | filename                               | uri                                             | path                                   |
      | civictheme_migrate.page_content_1.json | public://civictheme_migrate.page_content_1.json | civictheme_migrate.page_content_1.json |
      | civictheme_migrate.page_content_2.json | public://civictheme_migrate.page_content_2.json | civictheme_migrate.page_content_2.json |
    Given I am logged in as an administrator
    When I go to "admin/config/civictheme-migrate"
    And I select the radio button "Remote"
    And I fill in "Merlin extracted content JSON URL endpoints" with "http://nginx:8080/sites/default/files/civictheme_migrate.page_content_2.json"
    And I press the "Retrieve files" button
    And I should see the message "Merlin extracted content JSON files have been retrieved"
    And I press the "Generate migration" button
    And I should be in the "admin/structure/migrate/manage/civictheme_migrate/migrations" path
    And I visit "/admin/structure/migrate/manage/civictheme_migrate/migrations/civictheme_page_migrate/execute"
    And I press the "Execute" button
    # Arbituary wait while batch processes, extend wait time if it is failing.
    And I wait 10 seconds
    And I visit "[TEST] Migrated Content 3"
    And I visit "[TEST] Migrated Content 10"

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

