@p0 @civictheme @civictheme_navigation_card_ref
Feature: Navigation reference card render

  Background:
    Given "civictheme_page" content:
      | title                                       | status | field_c_n_site_section |
      | [TEST] Page Reference cards test            | 1      |                        |
      | [TEST] Reference Page Navigation cards test | 1      |                        |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Reference cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Reference cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title        | [TEST] Reference cards container |
      | field_c_p_column_count | 3                                |
      | field_c_p_fill_width   | 0                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Reference Page Navigation cards test |
      | field_c_p_theme     | light                                       |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Reference Page Navigation cards test |
      | field_c_p_theme     | light                                       |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Reference Page Navigation cards test |
      | field_c_p_theme     | dark                                        |

    When I visit "civictheme_page" "[TEST] Page Reference cards test"
    And I should see the text "[TEST] Reference cards container"
    And I should not see an ".ct-list__link a" element
    And I should see 1 ".ct-list" elements
    And I should see 3 ".ct-navigation-card__content" elements
