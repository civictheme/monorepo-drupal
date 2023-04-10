@p1 @civictheme @civictheme_migrate
Feature: CivicTheme migrate module access

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
