@p1 @civictheme @civictheme_snippet
Feature: Snippet render

  Background:
    Given "civictheme_page" content:
      | title                     | status |
      | [TEST] Page Snippets test | 1      |

    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Snippets
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Snippets test" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Snippet list                           |
      | field_c_p_list_column_count | 4                                             |
      | field_c_p_list_link_above   | 0: View all snippets - 1: https://example.com |
      | field_c_p_list_fill_width   | 0                                             |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Snippet list" has "civictheme_snippet" paragraph:
      | field_c_p_title   | Snippet title 1                       |
      | field_c_p_summary | Summary text  1                       |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_topics  | [TEST] Topic 1                        |
      | field_c_p_theme   | light                                 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Snippet list" has "civictheme_snippet" paragraph:
      | field_c_p_title   | Snippet title 2                       |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_topics  | [TEST] Topic 2, [TEST] Topic 3        |
      | field_c_p_theme   | dark                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Snippet list" has "civictheme_snippet" paragraph:
      | field_c_p_title   | Snippet title 3                       |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_theme   | dark                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Snippet list" has "civictheme_snippet" paragraph:
      | field_c_p_title | Snippet title 4                       |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |

    When I visit "civictheme_page" "[TEST] Page Snippets test"
    And I should see the text "[TEST] Snippet list"
    Then I should see the link "View all snippets" with "https://example.com" in '.ct-list'
    And I should see 4 ".ct-snippet" elements
    And I should see 4 ".ct-snippet__title" elements
    And I should see 3 ".ct-snippet__summary" elements
    And I should see the text "Snippet title 1"
    And I should see the text "Snippet title 2"
    And I should see the text "Snippet title 3"
    And I should see the text "Snippet title 4"
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Topic 2"
    And I should see the text "[TEST] Topic 3"
