@p1 @civictheme @content_type @civictheme_page
Feature: CivicTheme Page content type access

  Background:
    Given civictheme_page content:
      | title                                     | status | moderation_state |
      | [TEST] Test Published CivicTheme Page 1   | 1      | published        |
      | [TEST] Test Unpublished CivicTheme Page 1 | 0      | draft            |

  @api
  Scenario Outline: CivicTheme page content type access
    Given I am logged in as a user with the "<role>" role

    When I go to "node/add/civictheme_page"
    Then the response status code should be <add>

    When I visit civictheme_page "[TEST] Test Published CivicTheme Page 1"
    Then I should get a "<view>" HTTP response

    When I visit civictheme_page "[TEST] Test Unpublished CivicTheme Page 1"
    Then I should get a "<view_unpublished>" HTTP response

    When I edit civictheme_page "[TEST] Test Published CivicTheme Page 1"
    Then the response status code should be <edit>

    When I delete civictheme_page "[TEST] Test Published CivicTheme Page 1"
    And I should get a "<delete>" HTTP response

    Examples:
      | role                          | view | view_unpublished | add | edit | delete |
      | authenticated user            | 200  | 403              | 403 | 403  | 403    |
      | civictheme_content_author     | 200  | 200              | 200 | 200  | 200    |
      | civictheme_content_approver   | 200  | 200              | 403 | 403  | 403    |
      | civictheme_site_administrator | 200  | 200              | 200 | 200  | 200    |

  @api
  Scenario: CivicTheme page content type access anonymous user
    Given I am an anonymous user
    When I go to "node/add/civictheme_page"
    Then the response status code should be 403

    When I visit civictheme_page "[TEST] Test Published CivicTheme Page 1"
    Then I should get a 200 HTTP response

    When I visit civictheme_page "[TEST] Test Unpublished CivicTheme Page 1"
    Then I should get a 403 HTTP response

    When I edit civictheme_page "[TEST] Test Published CivicTheme Page 1"
    Then the response status code should be 403

    When I delete civictheme_page "[TEST] Test Published CivicTheme Page 1"
    And I should get a 403 HTTP response
