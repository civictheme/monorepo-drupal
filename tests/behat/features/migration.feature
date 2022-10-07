@civictheme @civictheme_migration
Feature: Tests the CivicTheme migration functionality

  @api @javascript
  Scenario: Migration configuration form should be setup correctly
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/config/civictheme-migration"
    Then I should see the text "Setup CivicTheme migration"
    And I select the radio button "Local"
    And I should see the text "Upload Merlin UI migration files"
    And I should not see the text "Connect to remote Merlin UI API to retrieve files"
    And I select the radio button "Remote"
    And I should see the text "Connect to remote Merlin UI API to retrieve files"
    And I should not see the text "Upload Merlin UI migration files"
    And I select the radio button "Username and password"
    And I should see a visible "input[name='auth_username']" element
    And I should see a visible "input[name='auth_password']" element
    And I should see a visible "input[name='auth_username']" element
    And I should not see a visible "input[name='auth_token']" element
    And I select the radio button "API token"
    And I should not see a visible "input[name='auth_username']" element
    And I should not see a visible "input[name='auth_password']" element
    And I should not see a visible "input[name='auth_username']" element
    And I should see a visible "input[name='auth_token']" element
    And I select the radio button "Basic authentication"
    And I should see a visible "input[name='auth_username']" element
    And I should see a visible "input[name='auth_password']" element
    And I should see a visible "input[name='auth_username']" element
    And I should not see a visible "input[name='auth_token']" element
    And I see the text "Merlin Generated JSON Endpoints"
    And I should see the button "Retrieve files"
    And I should see the button "Generate configuration"
    And I press the "Retrieve files" button
    And I should see the message containing "Merlin configuration files have been retrieved"
    And I should see the message containing "The configuration options have been saved."
    And I select the radio button "Local"
    And I press the "Generate configuration" button
    And I should not see the message "Merlin configuration files have been retrieved"
    And I should see the message "The configuration options have been saved."

  @api
  Scenario Outline: Only administrator can access the CivicTheme migration configuration form

    Given I am logged in as a user with the "<role>" role
    When I go to "admin/config/civictheme-migration"
    Then the response status code should be <code>

    Examples:
      | role                     | code |
      | civictheme_content_author     | 403  |
      | civictheme_content_approver   | 403  |
      | civictheme_site_administrator | 403  |
      | administrator            | 200  |

