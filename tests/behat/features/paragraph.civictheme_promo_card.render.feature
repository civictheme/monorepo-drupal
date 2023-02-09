@p0 @civictheme @civictheme_card @civictheme_promo_card
Feature: Promo card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                        | status | field_c_n_site_section |
      | [TEST] Page Promo cards test | 1      |                        |

    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Promo cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Promo manual list                         |
      | field_c_p_column_count    | 4                                                |
      | field_c_p_list_link_above | 0: View all promo cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_title    | Promo card title 1                    |
      | field_c_p_subtitle | Promo card subtitle 1                 |
      | field_c_p_summary  | Summary text 1                        |
      | field_c_p_image    | [TEST] CivicTheme Image               |
      | field_c_p_topics   | [TEST] Topic 1                        |
      | field_c_p_link     | 0: Test link - 1: https://example.com |
      | field_c_p_theme    | light                                 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_title    | Promo card title 2                    |
      | field_c_p_subtitle | Promo card subtitle 2                 |
      | field_c_p_summary  | Summary text 2                        |
      | field_c_p_image    | [TEST] CivicTheme Image               |
      | field_c_p_topics   | [TEST] Topic 2                        |
      | field_c_p_link     | 0: Test link - 1: https://example.com |
      | field_c_p_theme    | light                                 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_title    | Promo card title 3                    |
      | field_c_p_subtitle | Promo card subtitle 3                 |
      | field_c_p_summary  | Summary text 3                        |
      | field_c_p_image    | [TEST] CivicTheme Image               |
      | field_c_p_topics   | [TEST] Topic 3                        |
      | field_c_p_link     | 0: Test link - 1: https://example.com |
      | field_c_p_theme    | dark                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_title    | Promo card title 4                    |
      | field_c_p_subtitle | Promo card subtitle 4                 |
      | field_c_p_summary  | Summary text 4                        |
      | field_c_p_image    | [TEST] CivicTheme Image               |
      | field_c_p_link     | 0: Test link - 1: https://example.com |
      | field_c_p_theme    | dark                                  |

    When I visit "civictheme_page" "[TEST] Page Promo cards test"
    Then I should see the text "[TEST] Promo manual list"
    And I should see the link "View all promo cards" with "https://example.com" in '.ct-list'
    And I should see 1 ".ct-list" elements
    And I should see 4 ".ct-promo-card" elements
    And I should see 2 ".ct-promo-card.ct-theme-light" elements
    And I should see 2 ".ct-promo-card.ct-theme-dark" elements
    And I should see 4 ".ct-promo-card__content" elements
    And I should see 4 ".ct-promo-card__title" elements
    And I should see 4 ".ct-promo-card__summary" elements
    And I should see 3 ".ct-promo-card__tags .ct-tag" elements
    And I should see the text "Promo card title 1"
    And I should see the text "Promo card title 2"
    And I should see the text "Promo card title 3"
    And I should see the text "Promo card title 4"
    And I should see the text "Promo card subtitle 1"
    And I should see the text "Promo card subtitle 2"
    And I should see the text "Promo card subtitle 3"
    And I should see the text "Promo card subtitle 4"
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Topic 2"
    And I should see the text "[TEST] Topic 3"
