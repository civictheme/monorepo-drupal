@p1 @civictheme @block_type @block_civictheme_search
Feature: Search block render

  Background:
    Given "civictheme_search" block_content:
      | info     | field_c_b_link:title          | field_c_b_link:uri  | field_c_b_link_in_mobile_menu | status | region       |
      | Search 2 | [TEST] Search link            | http://example.com  | 1                             | 1      | header_top_1 |
      | Search 3 | [TEST] Non-Mobile Search link | http://example2.com | 0                             | 1      | header_top_1 |
    Given I create a block of type "Search 2" with:
      | label         | [TEST] Search link |
      | display_label | 0             |
      | region        | header_top_1  |
      | status        | 1             |
    Given I create a block of type "Search 3" with:
      | label         | [TEST] Non-Mobile Search link |
      | display_label | 0             |
      | region        | header_top_1  |
      | status        | 1             |
    And the cache has been cleared

  @api
  Scenario: CivicTheme search links appear as expected
    Given I am logged in as a user with the "Administrator" role
    And I visit "/admin/content/block"
    And save screenshot
    And I edit "civictheme_search" block_content_type with description "Search 2"
    And save screenshot
    Given I am an anonymous user
    When I visit "/"
    And save screenshot
    Then I should see an "a[href='http://example.com'].ct-search__link" element
    And I should see "[TEST] Search link" in the "a[href='http://example.com'].ct-search__link .ct-search__link-text" element
    Then I should see an "a[href='http://example.com'].ct-search__link" element
    And I should see "[TEST] Non-Mobile Search link" in the "a[href='http://example2.com']" element
    Then I should see an ".ct-menu__item a[href='http://example.com'].ct-menu__item__link" element
    And I should see "[TEST] Search link" in the ".ct-menu__item a[href='http://example.com'].ct-menu__item__link" element
    And I should see an ".ct-menu__item a[href='http://example.com'].ct-menu__item__link svg.ct-icon.ct-link__icon" element
    And I should not see an ".ct-menu__item a[href='http://example2.com'][title='[TEST] Non-Mobile Search link']" element

  @api @security
  Scenario: XSS - Search
    Given "civictheme_search" block_content:
      | info     | field_c_b_link:title          | field_c_b_link:uri  | field_c_b_link_in_mobile_menu | status | region       |
      | Search 4 | <script id="test-search--field_c_b_link_title">alert('[TEST] Search field_c_b_link_title')</script> | http://example.com  | 1                             | 1      | header_top_1 |
    Given I create a block of type "Search 4" with:
      | label         | [TEST] XSS Search link |
      | display_label | 0             |
      | region        | header_top_1  |
      | status        | 1             |
    And the cache has been cleared
    Given I am an anonymous user
    When I visit "/"
    Then I should not see an "script#test-search--field_c_b_link_title" element
    And I should see the text "alert('[TEST] Search field_c_b_link_title')"
