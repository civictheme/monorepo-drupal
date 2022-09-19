@civictheme @paragraph @civictheme_event_card
Feature: Tests the Event Card paragraph

  Ensure that Event Card paragraph renders correctly.

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with event cards
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                          | status |
      | [TEST] Page - Event cards test | 1      |

    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

    And I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page - Event cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title        | [TEST] Event manual list                 |
      | field_c_p_column_count | 4                                           |
      | field_c_p_list_link_above  | 0: View all events - 1: https://example.com |
      | field_c_p_fill_width   | 0                                           |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Event manual list" has "civictheme_event_card" paragraph:
      | field_c_p_date     | 2021-04-29                            |
      | field_c_p_image    | [TEST] CivicTheme Image               |
      | field_c_p_link     | 0: Test link - 1: https://example.com |
      | field_c_p_summary  | Summary text                          |
      | field_c_p_theme    | light                                 |
      | field_c_p_title    | Event card title                      |
      | field_c_p_topic    | [TEST] Topic 1                        |
      | field_c_p_location | [TEST] Location 1                     |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Event manual list" has "civictheme_event_card" paragraph:
      | field_c_p_date     | 2021-04-30                                  |
      | field_c_p_image    | [TEST] CivicTheme Image                     |
      | field_c_p_link     | 0: Test link - 1: https://example.com/card1 |
      | field_c_p_summary  | Summary text 2                              |
      | field_c_p_theme    | dark                                        |
      | field_c_p_title    | Event card title 1                          |
      | field_c_p_topic    | [TEST] Topic 2                              |
      | field_c_p_location | [TEST] Location 2                           |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Event manual list" has "civictheme_event_card" paragraph:
      | field_c_p_date     | 2022-05-29                                  |
      | field_c_p_image    | [TEST] CivicTheme Image                     |
      | field_c_p_link     | 0: Test link - 1: https://example.com/card2 |
      | field_c_p_summary  | Summary text 3                              |
      | field_c_p_theme    | light                                       |
      | field_c_p_title    | Event card title 2                          |
      | field_c_p_topic    | [TEST] Topic 1                              |
      | field_c_p_location | [TEST] Location 3                           |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Event manual list" has "civictheme_event_card" paragraph:
      | field_c_p_date     | 2023-06-29                                  |
      | field_c_p_image    | [TEST] CivicTheme Image                     |
      | field_c_p_link     | 0: Test link - 1: https://example.com/card3 |
      | field_c_p_summary  | Summary text 3                              |
      | field_c_p_theme    | dark                                        |
      | field_c_p_title    | Event card title 3                          |
      | field_c_p_location | [TEST] Location 4                           |

    When I visit "civictheme_page" "[TEST] Page - Event cards test"
    And I should see the text "[TEST] Event manual list"
    Then I should see the link "View all events" with "https://example.com" in 'div.ct-list'
    And I should see an "div.ct-event-card" element
    And I should see 1 "div.ct-list" elements
    And I should see 4 "div.ct-event-card" elements
    And I should see 2 "div.ct-event-card.ct-theme-light" elements
    And I should see 2 "div.ct-event-card.ct-theme-dark" elements
    And I should see 4 "div.ct-event-card__content" elements
    And I should see 4 "div.ct-event-card__title" elements
    And I should see 4 "div.ct-event-card__summary" elements
    And I should see 3 "div.ct-event-card__tags .ct-tag" elements
    And I should see the text "Event card title 1"
    And I should see the text "Event card title 2"
    And I should see the text "Event card title 3"
    And I should see the text "Event card title"
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Topic 2"
    And I should see the text "[TEST] Location 1"
    And I should see the text "[TEST] Location 2"
    And I should not see the text "[TEST] Topic 3"
    And I should see the text "29 Apr 2021"
    And I should see the text "29 May 2022"
    And I should see the text "29 Jun 2023"
    And save screenshot
