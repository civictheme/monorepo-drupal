@p0 @civictheme @civictheme_card @civictheme_navigation_card_ref
Feature: Navigation reference card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name           | field_c_m_image |
      | [TEST] Image 1 | test_image.jpg  |

    And "civictheme_page" content:
      | title                      | status | field_c_n_summary | field_c_n_thumbnail |
      | [TEST] Page with container | 1      |                   |                     |
      | [TEST] Referenced Page 1   | 1      | Summary 1         | [TEST] Image 1      |
      | [TEST] Referenced Page 2   | 1      | Summary 2         | [TEST] Image 1      |

  @api
  Scenario: Anonymous user can view Navigation reference card
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page with container" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Reference cards container |
      | field_c_p_list_column_count | 3                                |
      | field_c_p_list_fill_width   | 0                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page 1 |
      | field_c_p_theme     | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page 2 |
      | field_c_p_theme     | dark                     |

    When I visit "civictheme_page" "[TEST] Page with container"
    And I should see the text "[TEST] Reference cards container"
    And I should see 1 ".ct-list" elements
    And I should see 2 ".ct-navigation-card" elements
    And I should see 1 ".ct-navigation-card.ct-theme-light" elements
    And I should see 1 ".ct-navigation-card.ct-theme-dark" elements
    And I should see 2 ".ct-navigation-card__content" elements
    And I should see 2 ".ct-navigation-card__image" elements
    And I should see 2 ".ct-navigation-card__title" elements
    And I should see 2 ".ct-navigation-card__title__link" elements
    And I should see 2 ".ct-navigation-card__summary" elements
    And I should see the text "[TEST] Referenced Page 1"
    And I should see the text "Summary 1"
    And I should see the text "[TEST] Referenced Page 2"
    And I should see the text "Summary 2"
