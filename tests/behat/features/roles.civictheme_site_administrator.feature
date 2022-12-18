@p1 @civictheme @user_roles
Feature: Site administrator role

  @api
  Scenario: Site administrator user permissions
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/config/search/path"
    Then the response status code should be 200
    And I should see the text "URL aliases"
    And I save screenshot
    And I visit "admin/config/search/redirect"
    Then the response status code should be 200
    And I should see the text "Redirect"
