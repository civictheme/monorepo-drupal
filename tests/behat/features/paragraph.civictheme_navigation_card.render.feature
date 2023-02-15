@p1 @civictheme @civictheme_card @civictheme_navigation_card
Feature: Navigation card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                             | status | field_c_n_site_section |
      | [TEST] Page Navigation cards test | 1      |                        |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Navigation cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Navigation cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Navigation manual list                         |
      | field_c_p_list_column_count | 4                                                     |
      | field_c_p_list_link_above   | 0: View all navigation cards - 1: https://example.com |
      | field_c_p_list_fill_width   | 0                                                     |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Navigation card title                 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 1               |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 2               |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 3               |

    When I visit "civictheme_page" "[TEST] Page Navigation cards test"
    And I should see the text "[TEST] Navigation manual list"
    Then I should see the link "View all navigation cards" with "https://example.com" in '.ct-list'
    And I should see 4 ".ct-navigation-card" elements
    And I should see 4 ".ct-navigation-card__title" elements
    And I should see 4 ".ct-navigation-card__summary" elements
    And I should see the text "Navigation card title"
    And I should see the text "Navigation card title 1"
    And I should see the text "Navigation card title 2"
    And I should see the text "Navigation card title 3"
