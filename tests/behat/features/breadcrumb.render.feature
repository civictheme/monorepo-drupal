@p1 @civictheme @breadcrumb
Feature: Breadcrumb render

  Background:
    Given civictheme_page content:
      | title                              | status |
      | [TEST] Page breadcrumb parent test | 1      |
      | [TEST] Page breadcrumb test        | 1      |

  @api
  Scenario: CivicTheme breadcrumb component shows the name of the parent item on desktop and mobile.
    Given I am logged in as a user with the "Site Administrator" role
    When I edit "civictheme_page" "[TEST] Page breadcrumb parent test"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-breadcrumb-parent |
    And I press "Save"
    Then I edit "civictheme_page" "[TEST] Page breadcrumb test"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-breadcrumb-parent/breadcrumb-test |
    And I press "Save"
    When I visit "/test-breadcrumb-parent/breadcrumb-test"
    Then I should see the link "[TEST] Page breadcrumb parent test" with "/test-breadcrumb-parent" in "nav.ct-breadcrumb .ct-item-list.hide-l"
    And  I should see the link "[TEST] Page breadcrumb parent test" with "/test-breadcrumb-parent" in "nav.ct-breadcrumb .ct-item-list.show-l"
    And I should see "[TEST] Page breadcrumb test" in the ".ct-breadcrumb .ct-item-list.show-l" element
