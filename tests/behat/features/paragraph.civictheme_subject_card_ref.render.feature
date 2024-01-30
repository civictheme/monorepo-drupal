@p1 @civictheme @civictheme_card @civictheme_subject_card_ref
Feature: Subject reference card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name           | field_c_m_image | moderation_state | status |
      | [TEST] Image 1 | test_image.jpg  | published        | 1      |
    And "civictheme_page" content:
      | title                      | status | field_c_n_summary | field_c_n_thumbnail | moderation_state |
      | [TEST] Page with container | 1      |                   |                     | published        |
      | [TEST] Referenced Page 1   | 1      | Summary 1         | [TEST] Image 1      | published        |
      | [TEST] Referenced Page 2   | 1      | Summary 2         | [TEST] Image 1      | published        |

  @api
  Scenario: Anonymous user can view Subject reference card
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page with container" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Reference cards container |
      | field_c_p_list_column_count | 3                                |
      | field_c_p_list_fill_width   | 0                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page 1 |
      | field_c_p_theme     | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page 2 |
      | field_c_p_theme     | dark                     |

    When I visit "civictheme_page" "[TEST] Page with container"
    And I should see the text "[TEST] Reference cards container"
    And I should see 1 ".ct-list" elements
    And I should see 2 ".ct-subject-card" elements
    And I should see 1 ".ct-subject-card.ct-theme-light" elements
    And I should see 1 ".ct-subject-card.ct-theme-dark" elements
    And I should see 2 ".ct-subject-card__content" elements
    And I should see 2 ".ct-subject-card__image" elements
    And I should see 2 ".ct-subject-card__title" elements
    And I should see 2 ".ct-subject-card__title__link" elements
    And I should see the text "[TEST] Referenced Page 1"
    And I should see the text "[TEST] Referenced Page 2"
