@p1 @civictheme @user_roles
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

  @api
  Scenario: Site administrator role permissions
    Given I am logged in as a user with the "Site Administrator" role

    When I visit "/admin/config/search/path"
    Then the response status code should be 200
    And I should see the text "URL aliases"

    When I visit "admin/config/search/redirect"
    Then the response status code should be 200
    And I should see the text "Redirect"

    When I visit "admin/structure/webform"
    Then the response status code should be 200
    When I visit "admin/structure/webform/submissions/manage"
    Then the response status code should be 200
    When I visit "admin/structure/webform/options/manage"
    Then the response status code should be 200
    When I visit "admin/structure/webform/config"
    Then the response status code should be 200
    When I visit "admin/structure/webform/help"
    Then the response status code should be 200
