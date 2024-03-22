@search @p1
Feature: Search API

  As a site user, I want to search for content.

  @api
  Scenario: User searches for Page content
    Given civictheme_page content:
      | title                                     | status | field_c_n_summary                   |
      | [TEST] Search result 1 firstuniquestring  | 1      | Summary 1 firstuniquestringsummary  |
      | [TEST] Search result 2 seconduniquestring | 1      | Summary 2 seconduniquestringsummary |
      | [TEST] Search result 3 thirduniquestring  | 1      | Summary 3 thirduniquestringsummary  |
      | [TEST] Search result 4 forthuniquestring  | 1      | Summary 4 forthuniquestringsummary  |

    # Page 1, 3 searchable strings: 2 unique and 1 shared.
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 1 firstuniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                   |
      | field_c_p_content:value  | <h2>[TEST] Page 1 content 1</h2> <p>test content fourthuniquestring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                    |
      | field_c_p_background     | 0                                                                       |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 1 firstuniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                  |
      | field_c_p_content:value  | <h2>[TEST] Page 1 content 2</h2> <p>test content fifthuniquestring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                   |
      | field_c_p_background     | 0                                                                      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 1 firstuniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                  |
      | field_c_p_content:value  | <h2>[TEST] Page 1 content 3</h2> <p>test content firstsharedstring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                   |
      | field_c_p_background     | 0                                                                      |

    # Page 2, 3 searchable strings: 2 unique and 1 shared.
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 2 seconduniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                  |
      | field_c_p_content:value  | <h2>[TEST] Page 2 content 1</h2> <p>test content sixthuniquestring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                   |
      | field_c_p_background     | 0                                                                      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 2 seconduniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                    |
      | field_c_p_content:value  | <h2>[TEST] Page 2 content 2</h2> <p>test content seventhuniquestring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                     |
      | field_c_p_background     | 0                                                                        |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 2 seconduniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                  |
      | field_c_p_content:value  | <h2>[TEST] Page 2 content 3</h2> <p>test content firstsharedstring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                   |
      | field_c_p_background     | 0                                                                      |

    # Page 2, 3 searchable strings: 2 unique and 1 shared.
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 3 thirduniquestring" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                   |
      | field_c_p_content:value  | <h2>[TEST] Page 3 content 1</h2> <p>test content eighthuniquestring</p> |
      | field_c_p_content:format | civictheme_rich_text                                                    |
      | field_c_p_background     | 0                                                                       |

     # Page 4 searchable strings: Manual list.
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Search result 4 forthuniquestring" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Manual list title |
      | field_c_p_list_column_count | 4                        |
      | field_c_p_list_fill_width   | 0                        |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 1 testtestecardtitle1      |
      | field_c_p_summary | Card summary 1  testtestecardsummary1 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 2 testtestecardtitle2     |
      | field_c_p_summary | Card summary 2 testtestecardsummary1 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 3              |
      | field_c_p_summary | testtesttestecardsummary3 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 4              |
      | field_c_p_summary | testtesttestecardsummary4 |

    And I index "civictheme_page" "[TEST] Search result 1 firstuniquestring" for search
    And I index "civictheme_page" "[TEST] Search result 2 seconduniquestring" for search
    And I index "civictheme_page" "[TEST] Search result 3 thirduniquestring" for search
    And I index "civictheme_page" "[TEST] Search result 4 forthuniquestring" for search

    And I go to the homepage
    And I click "Search"
    And I wait 2 seconds
    Then I should see "Search" in the ".ct-heading" element
    And I should not see a ".ct-side-navigation" element

    # Search for common strings.
    When I fill in "keywords" with "test"
    And I press "Search"
    Then I should see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should see "firstuniquestringsummary" in the ".ct-list" element
    And I should see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should see "seconduniquestringsummary" in the ".ct-list" element
    And I should see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element
    And I should see "thirduniquestringsummary" in the ".ct-list" element

    # Search for unique strings in Title.
    When I fill in "keywords" with "firstuniquestring"
    And I press "Search"
    Then I should see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element

    # Search for unique strings in Summary.
    When I fill in "keywords" with "seconduniquestringsummary"
    And I press "Search"
    Then I should not see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element

    # Search for unique strings in content.
    When I fill in "keywords" with "fourthuniquestring"
    And I press "Search"
    Then I should see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element

    # Search for unique strings in content across multiple pages.
    When I fill in "keywords" with "fourthuniquestring sixthuniquestring"
    And I press "Search"
    Then I should see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element

    # Search for shared strings in content across multiple pages.
    When I fill in "keywords" with "firstsharedstring"
    And I press "Search"
    Then I should see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should not see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element

    # Search for unique and shared strings in content across multiple pages.
    When I fill in "keywords" with "firstsharedstring eighthuniquestring"
    And I press "Search"
    Then I should see "[TEST] Search result 1 firstuniquestring" in the ".ct-list" element
    And I should see "[TEST] Search result 2 seconduniquestring" in the ".ct-list" element
    And I should see "[TEST] Search result 3 thirduniquestring" in the ".ct-list" element

    # Search for manual list strings.
    When I fill in "keywords" with "testtestecardtitle1"
    And I press "Search"
    Then I should see "[TEST] Search result 4 forthuniquestring" in the ".ct-list" element

    When I fill in "keywords" with "testtestecardsummary1"
    And I press "Search"
    Then I should see "[TEST] Search result 4 forthuniquestring" in the ".ct-list" element

    When I fill in "keywords" with "testtesttestecardsummary3"
    And I press "Search"
    Then I should see "[TEST] Search result 4 forthuniquestring" in the ".ct-list" element

    When I fill in "keywords" with "testtesttestecardsummary4"
    And I press "Search"
    Then I should see "[TEST] Search result 4 forthuniquestring" in the ".ct-list" element
