@civic @paragraph @civic_card_event
Feature: Tests the Event Card paragraph

  Ensure that Event Card paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Event card" in the "civic_card_event" row

  @api
  Scenario: Event card paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_event/fields"
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_date" in the "Date" row
    And I should see the text "field_c_p_topic" in the "Topic" row
    And I should see the text "field_c_p_location" in the "Location" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civic_card_container_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-header-link-0-uri" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-column-count select.required" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_column_count]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_fill_width][value]']" element
    And I click on "div.field--name-field-c-p-cards .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_0_subform_field_c_p_cards_civic_card_event_add_more" button
    And I wait for AJAX to finish
    Then select "field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]" should have an option "light"
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]'].required" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]'].required" element
    And I should see an "div.field--name-field-c-p-image.field--widget-media-library-widget .js-media-library-widget" element
    And I should see an "div.field--name-field-c-p-image.field--widget-media-library-widget .js-media-library-widget.required" element
    And I should see an "textarea[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]']" element
    And I should see an "textarea[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]'].required" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_date][0][value][date]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]'].required" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][title]']" element
      And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_topic][0][target_id]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_location][0][value]']" element
    And the option "Light" from select "Theme" is selected

  @api @javascript
  Scenario: Civic page content type page can be viewed by anonymous with event cards
    Given managed file:
      | filename       | uri                                | path           |
      | test_image.jpg | public://civic_test/test_image.jpg | test_image.jpg |

    And "civic_image" media:
      | name               | field_c_m_image |
      | [TEST] Civic Image | test_image.jpg  |

    And "civic_page" content:
      | title                          | status |
      | [TEST] Page - Event cards test | 1      |

    And "civic_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

    And I am an anonymous user
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
      | field_c_p_location   | [TEST] Location 1                  |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2021-04-30                                  |
      | field_c_p_image   | [TEST] Civic Image                          |
      | field_c_p_link    | 0: Test link - 1: https://example.com/card1 |
      | field_c_p_summary | Summary text 2                              |
      | field_c_p_theme   | dark                                        |
      | field_c_p_title   | Event card title 1                          |
      | field_c_p_topic   | [TEST] Topic 2                              |
      | field_c_p_location   | [TEST] Location 2                        |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2022-05-30                                  |
      | field_c_p_image   | [TEST] Civic Image                          |
      | field_c_p_link    | 0: Test link - 1: https://example.com/card2 |
      | field_c_p_summary | Summary text 3                              |
      | field_c_p_theme   | light                                       |
      | field_c_p_title   | Event card title 2                          |
      | field_c_p_topic   | [TEST] Topic 1                              |
      | field_c_p_location   | [TEST] Location 3                        |
    And "field_c_p_cards" in "civic_card_container" "paragraph" with "field_c_p_title" of "[TEST] Event card container" has "civic_card_event" paragraph:
      | field_c_p_date    | 2023-06-30                                  |
      | field_c_p_image   | [TEST] Civic Image                          |
      | field_c_p_link    | 0: Test link - 1: https://example.com/card3 |
      | field_c_p_summary | Summary text 3                              |
      | field_c_p_theme   | dark                                        |
      | field_c_p_title   | Event card title 3                          |
      | field_c_p_location   | [TEST] Location 4                        |

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
    And I should see 3 "div.civic-event-card__tags .civic-tag" elements
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
