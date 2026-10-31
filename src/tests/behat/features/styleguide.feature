@p0 @civictheme @styleguide
Feature: Styleguide loads correctly

  @api
  Scenario: The CivicTheme styleguide loads correctly
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200
