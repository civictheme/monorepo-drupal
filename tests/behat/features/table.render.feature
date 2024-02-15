@p0 @civictheme @civictheme_table
Feature: Table render

  @api @testmode
  Scenario: Views page should show table with correct markup.
    Given civictheme_page content:
      | title         | status | created                  |
      | [TEST] Page 1 | 1      | [relative:1 second ago]  |
      | [TEST] Page 2 | 1      | [relative:2 seconds ago] |
      | [TEST] Page 3 | 1      | [relative:3 seconds ago] |
      | [TEST] Page 4 | 1      | [relative:4 seconds ago] |
      | [TEST] Page 5 | 1      | [relative:5 seconds ago] |
      | [TEST] Page 6 | 1      | [relative:6 seconds ago] |

    When I visit "civictheme-no-sidebar/table-full-pager"
    Then I should see the text "CivicTheme test table"
    And I should see a "table.ct-table" element
    And I should see the text "[TEST] Page 1"
    And I should see the text "[TEST] Page 2"
    And I should see the text "[TEST] Page 3"
    And I should see the text "[TEST] Page 4"
    And I should see the text "[TEST] Page 5"
    And I should not see the text "[TEST] Page 6"
    # Assert that link processing works.
    And I should see "[TEST] Page 1" in the ".ct-table .ct-content-link" element
