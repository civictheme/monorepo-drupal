@civic @civic_page @civic_page_view
Feature: View of Page content type

  Ensure that Page content can be viewed correctly.

  Background:
    Given managed file:
      | filename                      | uri                                                    | path           |
      | test_image.jpg                | public://civic_test/test_image.jpg                     | test_image.jpg |

    And "image" media:
      | name                             | field_media_image             |
      | [TEST] Civic Image               | test_image.jpg                |

    And "civic_page" content:
      | title                               | status   |
      | [TEST] Page - Promo cards test      | 1        |
      | [TEST] Page - Navigation cards test | 1        |
      | [TEST] Page - Event cards test      | 1        |
      | [TEST] Page - Subject cards test    | 1        |
      | [TEST] Page - Service cards test    | 1        |

  @api @javascript
  Scenario: Civic page content type page can be viewed by anonymous with promo cards
    Given I am an anonymous user
    And "field_c_n_components" in "civic_page" "node" with "title" of "[TEST] Page - Promo cards test" has "civic_card_container" paragraph:
      | field_c_p_title                  | [TEST] Promo card container    |
      | field_c_p_column_count           | 4                              |
      | field_c_p_link | 0: View all promo cards - 1: https://example.com |
      | field_c_p_fill_width             | 0                              |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civic_card_promo" paragraph:
      | field_c_p_date                 | 2021-04-30                            |
      | field_c_p_image                | [TEST] Civic Image                    |
      | field_c_p_link                 | 0: Test link - 1: https://example.com |
      | field_c_p_summary              | Summary text                          |
      | field_c_p_theme                | light                                 |
      | field_c_p_title                | Promo card title                      |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civic_card_promo" paragraph:
      | field_c_p_date                 | 2021-04-30                            |
      | field_c_p_image                | [TEST] Civic Image                    |
      | field_c_p_link                 | 0: Test link - 1: https://example.com |
      | field_c_p_summary              | Summary text 2                        |
      | field_c_p_theme                | dark                                  |
      | field_c_p_title                | Promo card title 1                    |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civic_card_promo" paragraph:
      | field_c_p_date                 | 2021-04-30                            |
      | field_c_p_image                | [TEST] Civic Image                    |
      | field_c_p_link                 | 0: Test link - 1: https://example.com |
      | field_c_p_summary              | Summary text 3                        |
      | field_c_p_theme                | dark                                  |
      | field_c_p_title                | Promo card title 2                    |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civic_card_promo" paragraph:
      | field_c_p_date                 | 2021-04-30                            |
      | field_c_p_image                | [TEST] Civic Image                    |
      | field_c_p_link                 | 0: Test link - 1: https://example.com |
      | field_c_p_summary              | Summary text 3                        |
      | field_c_p_theme                | dark                                  |
      | field_c_p_title                | Promo card title 3                    |

    When I visit "civic_page" "[TEST] Page - Promo cards test"
      And I should see the text "[TEST] Promo card container"
      Then I should see the link "View all promo cards" with "https://example.com" in 'div.civic-card-container'
      And I should see an "div.civic-promo-card" element
      And I should see the text "Promo card title"
      And I should see the text "Promo card title 1"
      And I should see the text "Promo card title 2"
      And I should see the text "Promo card title 3"
      And save screenshot

