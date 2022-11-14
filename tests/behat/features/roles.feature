@civictheme @user_roles
Feature: Roles

  @api
  Scenario Outline: User with assigned role visits homepage
    Given I am logged in as a user with the "<role>" role
    And I go to the homepage
    And I save screenshot
    Examples:
      | role                          |
      | civictheme_content_author     |
      | civictheme_content_approver   |
      | civictheme_site_administrator |
