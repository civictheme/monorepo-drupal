@p0 @civictheme @civictheme_snippet_ref
Feature: Navigation reference card render

  Background:
    Given "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

    And "civictheme_page" content:
      | title                      | status | field_c_n_summary | field_c_n_topics               |
      | [TEST] Page with container | 1      |                   |                                |
      | [TEST] Referenced Page 1   | 1      | Summary 1         | [TEST] Topic 1                 |
      | [TEST] Referenced Page 2   | 1      | Summary 2         | [TEST] Topic 2, [TEST] Topic 3 |

  @api
  Scenario: Anonymous user can view Navigation reference card
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page with container" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Reference list title |
      | field_c_p_list_column_count | 3                           |
      | field_c_p_list_fill_width   | 0                           |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference list title" has "civictheme_snippet_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page 1 |
      | field_c_p_theme     | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference list title" has "civictheme_snippet_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page 2 |
      | field_c_p_theme     | dark                     |

    When I visit "civictheme_page" "[TEST] Page with container"
    And I should see the text "[TEST] Reference list title"
    And I should see 1 ".ct-list" elements
    And I should see 2 ".ct-snippet" elements
    And I should see 1 ".ct-snippet.ct-theme-light" elements
    And I should see 1 ".ct-snippet.ct-theme-dark" elements
    And I should see 2 ".ct-snippet__title" elements
    And I should see 2 ".ct-snippet__summary" elements
    And I should see 2 ".ct-snippet__title-link" elements
    And I should see 2 ".ct-snippet__tags" elements
    And I should see the text "[TEST] Referenced Page 1"
    And I should see the text "Summary 1"
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Referenced Page 2"
    And I should see the text "Summary 2"
    And I should see the text "[TEST] Topic 2"
    And I should see the text "[TEST] Topic 3"
