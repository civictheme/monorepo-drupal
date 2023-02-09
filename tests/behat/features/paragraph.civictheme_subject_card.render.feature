@p1 @civictheme @civictheme_card @civictheme_subject_card
Feature: Subject card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Subject cards
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                          | status | field_c_n_site_section |
      | [TEST] Page Subject cards test | 1      |                        |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Subject cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Subject manual list                         |
      | field_c_p_column_count    | 3                                                  |
      | field_c_p_list_link_above | 0: View all Subject cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Subject manual list" has "civictheme_subject_card" paragraph:
      | field_c_p_image | [TEST] CivicTheme Image               |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | light                                 |
      | field_c_p_title | Subject card title 1                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Subject manual list" has "civictheme_subject_card" paragraph:
      | field_c_p_image | [TEST] CivicTheme Image               |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |
      | field_c_p_title | Subject card title 2                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Subject manual list" has "civictheme_subject_card" paragraph:
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |
      | field_c_p_title | Subject card title 3                  |

    When I visit "civictheme_page" "[TEST] Page Subject cards test"
    And I should see the text "[TEST] Subject manual list"
    Then I should see the link "View all Subject cards" with "https://example.com" in '.ct-list'
    And I should see 1 ".ct-list" elements
    And I should see 3 ".ct-subject-card" elements
    And I should see 2 ".ct-subject-card__content__image img" elements
    And I should see 3 ".ct-subject-card__content__title" elements
    And I should not see an ".ct-navigation-card" element
    And I should see the text "Subject card title 1"
    And I should see the text "Subject card title 2"
    And I should see the text "Subject card title 3"
