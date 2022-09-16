@civictheme @civictheme_table @testmode
Feature: Tests the Table

  Ensure that Table is rendering correctly.

  @api
  Scenario: Styleguide page should show table with correct markup.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide/civictheme#table"
    Then I should see the text "Table"
    And I should see a "table.civictheme-table" element

  @api
  Scenario: Views page should show table with correct markup.
    Given civictheme_page content:
      | title            | status |
      | [TEST] Page 1    | 1      |
      | [TEST] Page 2    | 1      |
      | [TEST] Page 3    | 1      |
      | [TEST] Page 4    | 1      |
      | [TEST] Page 5    | 1      |

    When I visit "civictheme-no-sidebar/test-table"
    Then I should see the text "Civictheme test table"
    And I should see a "table.civictheme-table" element
    And I should see the text "[TEST] Page 1"
