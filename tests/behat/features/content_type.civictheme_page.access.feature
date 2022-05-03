@civictheme @civictheme_page
Feature: View of Page content type

  Ensure that Page content can be viewed correctly.

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
      | anonymous user                | 200  | 403              | 403 | 403  | 403    |
      | authenticated user            | 200  | 403              | 403 | 403  | 403    |
      | civictheme_content_author     | 200  | 200              | 200 | 200  | 200    |
      | civictheme_content_approver   | 200  | 200              | 403 | 403  | 403    |
      | civictheme_site_administrator | 200  | 200              | 200 | 200  | 200    |
