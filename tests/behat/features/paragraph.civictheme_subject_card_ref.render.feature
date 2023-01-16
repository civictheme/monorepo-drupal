@p1 @civictheme @civictheme_subject_card_ref
Feature: Subject reference card render

  Background:

    Given "civictheme_page" content:
      | title                               | status |
      | [TEST] Page Subject reference cards | 1      |
      | [TEST] Referenced page 1            | 1      |
      | [TEST] Referenced page 2            | 1      |
      | [TEST] Referenced page 3            | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Reference cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Subject reference cards" has "civictheme_manual_list" paragraph:
      | field_c_p_title        | [TEST] Reference cards container |
      | field_c_p_column_count | 3                                |
      | field_c_p_fill_width   | 0                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced page 1 |
      | field_c_p_theme     | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced page 2 |
      | field_c_p_theme     | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced page 3 |
      | field_c_p_theme     | dark                     |

    When I visit "civictheme_page" "[TEST] Page Subject reference cards"
    And I should see the text "[TEST] Reference cards container"
    And I should not see an ".ct-list__link a" element
    And I should see 1 ".ct-list" elements
    And I should see 3 ".ct-subject-card__content" elements
    And I should see the text "Referenced page 1"
    And I should see the text "Referenced page 2"
    And I should see the text "Referenced page 3"
