@p0 @civictheme @civictheme_promo_card
Feature: Promo card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |

    Given "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    Given "civictheme_page" content:
      | title                        | status | field_c_n_site_section |
      | [TEST] Page Promo cards test | 1      |                        |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Promo cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Promo manual list                         |
      | field_c_p_column_count    | 4                                                |
      | field_c_p_list_link_above | 0: View all promo cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Promo card title                      |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 1                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 2                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 3                    |

    When I visit "civictheme_page" "[TEST] Page Promo cards test"
    And I should see the text "[TEST] Promo manual list"
    Then I should see the link "View all promo cards" with "https://example.com" in '.ct-list'
    And I should see an ".ct-promo-card" element
    And I should see the text "Promo card title"
    And I should see the text "Promo card title 1"
    And I should see the text "Promo card title 2"
    And I should see the text "Promo card title 3"
