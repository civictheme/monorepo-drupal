@civictheme @breadcrumb
Feature: Tests the CivicTheme breadcrumb

  Ensure that the breadcrumb are displayed correctly.

  Background:
    Given "civictheme_page" content:
      | title                              | status | path[0][pathauto] | path[0][alias]                          |
      | [TEST] Page breadcrumb parent test | 1      | 0                 | /test-breadcrumb-parent                 |
      | [TEST] Page breadcrumb test        | 1      | 0                 | /test-breadcrumb-parent/breadcrumb-test |

  @api
  Scenario: CivicTheme breadcrumb component shows the name of the parent item on the mobile.
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Page breadcrumb test"
    Then I should see the link "[TEST] Page breadcrumb parent test" with "/test-breadcrumb-parent" in 'nav.civictheme-breadcrumb li.civictheme-breadcrumb__links--link.mobile-only'
