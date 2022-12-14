@p1 @civictheme @civictheme_pagination @testmode
Feature: Pagination

  Background:
    Given civictheme_page content:
      | title          | status |
      | [TEST] Page 1  | 1      |
      | [TEST] Page 2  | 1      |
      | [TEST] Page 3  | 1      |
      | [TEST] Page 4  | 1      |
      | [TEST] Page 5  | 1      |
      | [TEST] Page 6  | 1      |
      | [TEST] Page 7  | 1      |
      | [TEST] Page 8  | 1      |
      | [TEST] Page 9  | 1      |
      | [TEST] Page 10 | 1      |
      | [TEST] Page 11 | 1      |
      | [TEST] Page 12 | 1      |
      | [TEST] Page 13 | 1      |
      | [TEST] Page 14 | 1      |
      | [TEST] Page 15 | 1      |
      | [TEST] Page 16 | 1      |
      | [TEST] Page 17 | 1      |
      | [TEST] Page 18 | 1      |
      | [TEST] Page 19 | 1      |
      | [TEST] Page 20 | 1      |
      | [TEST] Page 21 | 1      |
      | [TEST] Page 22 | 1      |
      | [TEST] Page 23 | 1      |
      | [TEST] Page 24 | 1      |
      | [TEST] Page 25 | 1      |

  @api @d9only
  Scenario: Styleguide page should show table with correct markup.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide/civictheme#pagination-heading"
    Then I should see the text "Pager"
    And I should see a "nav.ct-pager" element

  @api @skipped
  Scenario: Views page with full pager and items per page should render and function correctly
    Given I am an anonymous user
    When I visit "civictheme-no-sidebar/test-table"
    Then I should see the text "Civictheme test table"
    Then I should see an ".ct-list table.ct-table" element
    And I should see 5 ".ct-list table.ct-table tbody tr" elements
    # Ensure full pager is rendered correctly and links are working as expected.
    And I should see an ".ct-list__results-below .ct-pager" element
    And I should not see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Prev" in the ".ct-list__results-below .ct-pager .ct-pager__link[disabled]" element
    And I should see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see a ".ct-pager .ct-pager__item--ellipsis-next" element
    And I should not see a ".ct-pager .ct-pager__item--ellipsis-previous" element
    And I click "2"
    And I should see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see "First" in the ".ct-list__results-below .ct-pager" element
    And I click "Last"
    And I should not see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Next" in the ".ct-list__results-below .ct-pager .ct-pager__link[disabled]" element
    And I should see "Items per page" in the ".ct-list__results-below .ct-pager .ct-pager__items_per_page" element
    And I should not see a ".ct-pager .ct-pager__item--ellipsis-next" element
    And I should see a ".ct-pager .ct-pager__item--ellipsis-previous" element
    And select "items_per_page" should have an option "5"
    And select "items_per_page" should have an option "10"
    And select "items_per_page" should have an option "25"
    And select "items_per_page" should have an option "50"
    And the option "5" from select "items_per_page" is selected
    And I select "25" from "items_per_page"
    And I press "Apply"
    And I should see an ".ct-list table.ct-table" element
    And I should see 25 ".ct-list table.ct-table tbody tr" elements
    And the option "25" from select "items_per_page" is selected

  @api
  Scenario: Views page with mini pager and items per page should render and function correctly
    Given I am an anonymous user
    When I visit "civictheme-no-sidebar/test-table-mini-pager"
    Then I should see the text "Civictheme test table"
    Then I should see an ".ct-list table.ct-table" element
    And I should see 5 ".ct-list table.ct-table tbody tr" elements
    And I should see an ".ct-list__results-below .ct-pager" element
    And I should not see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Prev" in the ".ct-list__results-below .ct-pager .ct-pager__link[disabled]" element
    And I should see "1" in the ".ct-list__results-below .ct-pager .ct-pager__link.ct-link--active" element
    And I should not see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see a ".ct-pager .ct-pager__item--ellipsis-next" element
    And I should not see a ".ct-pager .ct-pager__item--ellipsis-previous" element
    And I click "Next"
    And I should see a ".ct-pager .ct-pager__item--ellipsis-next" element
    And I should see a ".ct-pager .ct-pager__item--ellipsis-previous" element
    And I should not see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should not see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "2" in the ".ct-list__results-below .ct-pager .ct-pager__link.ct-link--active" element
    And I click "Prev"
    And I should see "Prev" in the ".ct-list__results-below .ct-pager .ct-pager__link[disabled]" element
    And I should see "1" in the ".ct-list__results-below .ct-pager .ct-pager__link.ct-link--active" element
    And I should see "Items per page" in the ".ct-list__results-below .ct-pager .ct-pager__items_per_page" element
    And select "items_per_page" should have an option "5"
    And select "items_per_page" should have an option "10"
    And select "items_per_page" should have an option "25"
    And select "items_per_page" should have an option "50"
    And the option "5" from select "items_per_page" is selected
    And I select "25" from "items_per_page"
    And I press "Apply"
    And I should see an ".ct-list table.ct-table" element
    And I should see 25 ".ct-list table.ct-table tbody tr" elements
    And the option "25" from select "items_per_page" is selected
