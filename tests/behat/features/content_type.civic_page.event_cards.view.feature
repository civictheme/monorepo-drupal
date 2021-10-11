@civic @civic_event_cards
Feature: View of Page content type with event cards

  Ensure that Page content can be viewed correctly.

  Background:
    Given managed file:
      | filename       | uri                                | path           |
      | test_image.jpg | public://civic_test/test_image.jpg | test_image.jpg |

    And "image" media:
      | name               | field_media_image |
      | [TEST] Civic Image | test_image.jpg    |

    And "civic_page" content:
      | title                          | status |
      | [TEST] Page - Event cards test | 1      |

    And "civic_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

  @api @javascript
  Scenario: Civic page content type page can be viewed by anonymous with event cards
    Given I am an anonymous user
    And "field_c_n_components" in "civic_page" "node" with "title" of "[TEST] Page - Event cards test" has "civic_card_container" paragraph:
      | field_c_p_title        | [TEST] Event card container                 |
      | field_c_p_column_count | 4                                           |
      | field_c_p_header_link  | 0: View all events - 1: https://example.com |
      | field_c_p_fill_width   | 0                                           |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] Civic Image                    |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Event card title                      |
      | field_c_p_topic   | [TEST] Topic 1                        |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2021-04-30                                  |
      | field_c_p_image   | [TEST] Civic Image                          |
      | field_c_p_link    | 0: Test link - 1: https://example.com/card1 |
      | field_c_p_summary | Summary text 2                              |
      | field_c_p_theme   | dark                                        |
      | field_c_p_title   | Event card title 1                          |
      | field_c_p_topic   | [TEST] Topic 2                              |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2022-05-30                                  |
      | field_c_p_image   | [TEST] Civic Image                          |
      | field_c_p_link    | 0: Test link - 1: https://example.com/card2 |
      | field_c_p_summary | Summary text 3                              |
      | field_c_p_theme   | light                                       |
      | field_c_p_title   | Event card title 2                          |
      | field_c_p_topic   | [TEST] Topic 1                              |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2023-06-30                                  |
      | field_c_p_image   | [TEST] Civic Image                          |
      | field_c_p_link    | 0: Test link - 1: https://example.com/card3 |
      | field_c_p_summary | Summary text 3                              |
      | field_c_p_theme   | dark                                        |
      | field_c_p_title   | Event card title 3                          |

    When I visit "civic_page" "[TEST] Page - Event cards test"
    And I should see the text "[TEST] Event card container"
    Then I should see the link "View all events" with "https://example.com" in 'div.civic-card-container'
    And I should see an "div.civic-event-card" element
    And I should see 1 "div.civic-card-container" elements
    And I should see 4 "div.civic-event-card" elements
    And I should see 2 "div.civic-event-card.civic-theme-light" elements
    And I should see 2 "div.civic-event-card.civic-theme-dark" elements
    And I should see 4 "div.civic-event-card__content" elements
    And I should see 4 "div.civic-event-card__title" elements
    And I should see 4 "div.civic-event-card__summary" elements
    And I should see 3 "div.civic-event-card__tags .civic-tag--card" elements
    And I should see the text "Event card title 1"
    And I should see the text "Event card title 2"
    And I should see the text "Event card title 3"
    And I should see the text "Event card title"
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Topic 2"
    And I should not see the text "[TEST] Topic 3"
    And I should see the text "29 Apr 2021"
    And I should see the text "29 May 2022"
    And I should see the text "29 Jun 2023"
    And save screenshot
