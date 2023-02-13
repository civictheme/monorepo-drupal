@p1 @civictheme @civictheme_card @civictheme_event_card_ref
Feature: Event reference card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name           | field_c_m_image |
      | [TEST] Image 1 | test_image.jpg  |

    And "civictheme_topics" terms:
      | name            |
      | [TEST] Topic 11 |
      | [TEST] Topic 12 |
      | [TEST] Topic 21 |
      | [TEST] Topic 22 |

    And "civictheme_page" content:
      | title                      | status |
      | [TEST] Page with container | 1      |

    And "civictheme_event" content:
      | title                     | status | field_c_n_summary | field_c_n_thumbnail | field_c_n_topics                 | field_c_n_date_range:value | field_c_n_date_range:end_value |
      | [TEST] Referenced Event 1 | 1      | Summary 1         | [TEST] Image 1      | [TEST] Topic 11, [TEST] Topic 12 | 2022-07-01T09:45:00        | 2022-08-14T11:30:00            |
      | [TEST] Referenced Event 2 | 1      | Summary 2         | [TEST] Image 1      | [TEST] Topic 21, [TEST] Topic 22 | 2023-07-01T09:45:00        | 2023-08-14T11:30:00            |

  @api
  Scenario: Anonymous user can view Event reference card
    Given I am an anonymous user

    And "field_c_n_location" in "civictheme_event" "node" with "title" of "[TEST] Referenced Event 1" has "civictheme_map" paragraph:
      | field_c_p_embed_url | 0: [TEST] link 1 - 1: https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed |
      | field_c_p_address   | Address 1                                                                                         |
    And "field_c_n_location" in "civictheme_event" "node" with "title" of "[TEST] Referenced Event 2" has "civictheme_map" paragraph:
      | field_c_p_embed_url | 0: [TEST] link 1 - 1: https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed |
      | field_c_p_address   | Address 2                                                                                         |

    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page with container" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Reference cards container |
      | field_c_p_list_column_count | 3                                |
      | field_c_p_list_fill_width   | 0                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_event_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Event 1 |
      | field_c_p_theme     | light                     |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_event_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Event 2 |
      | field_c_p_theme     | dark                      |

    When I visit "civictheme_page" "[TEST] Page with container"
    And I should see the text "[TEST] Reference cards container"
    And I should see 1 ".ct-list" elements
    And I should see 2 ".ct-event-card" elements
    And I should see 1 ".ct-event-card.ct-theme-light" elements
    And I should see 1 ".ct-event-card.ct-theme-dark" elements
    And I should see 2 ".ct-event-card__date" elements
    And I should see 2 ".ct-event-card__content" elements
    And I should see 2 ".ct-event-card__location" elements
    And I should see 2 ".ct-event-card__image" elements
    And I should see 2 ".ct-event-card__title" elements
    And I should see 2 ".ct-event-card__title__link" elements
    And I should see 2 ".ct-event-card__summary" elements
    And I should see 2 ".ct-event-card__location" elements
    And I should see the text "[TEST] Referenced Event 1"
    And I should see the text "1 Jul 2022"
    And I should see the text "14 Aug 2022"
    And I should see the text "Address 1"
    And I should see the text "Summary 1"
    And I should see the text "Topic 11"
    And I should see the text "Topic 12"
    And I should see the text "[TEST] Referenced Event 2"
    And I should see the text "1 Jul 2023"
    And I should see the text "14 Aug 2023"
    And I should see the text "Address 2"
    And I should see the text "Summary 2"
    And I should see the text "Topic 21"
    And I should see the text "Topic 22"
