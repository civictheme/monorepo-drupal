@civic @user_roles
Feature: Roles

  Ensure that all roles exist on the site.

  @api
  Scenario Outline: User with assigned role visits homepage
    Given I am logged in as a user with the "<role>" role
    And I go to the homepage
    And I save screenshot
    Examples:
      | role                      |
      | civic_content_author      |
      | civic_content_approver    |
      | civic_site_administrator  |
