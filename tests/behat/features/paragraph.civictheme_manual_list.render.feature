@p0 @civictheme @civictheme_manual_list
Feature: Manual list render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                           | status |
      | [TEST] Page Manual list content | 1      |
      | [TEST] Referenced Page          | 1      |

    And "civictheme_event" content:
      | title                   | status |
      | [TEST] Referenced Event | 1      |

  @api
  Scenario: Manual list, no results
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Manual list content" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Manual list title                            |
      | field_c_p_content:value     | [TEST] Manual list content                          |
      | field_c_p_content:format    | civictheme_rich_text                                |
      | field_c_p_list_link_above   | 0: [TEST] Link above - 1: https://example.com/above |
      | field_c_p_list_link_below   | 0: [TEST] Link below - 1: https://example.com/below |
      | field_c_p_list_column_count | 4                                                   |
      | field_c_p_list_fill_width   | 0                                                   |
      | field_c_p_theme             | light                                               |
      | field_c_p_background        | 1                                                   |
      | field_c_p_vertical_spacing  | both                                                |

    When I visit "civictheme_page" "[TEST] Page Manual list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And I should see a ".ct-list.ct-list--with-background" element
    And I should see a ".ct-list.ct-vertical-spacing-inset--both" element
    And the response should contain "ct-manual-list-"
    And I should see the text "[TEST] Manual list title"
    And I should see the text "[TEST] Manual list content"
    And I should see the link "[TEST] Link above" with "https://example.com/above" in '.ct-list'
    And I should see the link "[TEST] Link below" with "https://example.com/below" in '.ct-list'
    # No results.
    And I should not see a ".ct-list__body" element

  @api
  Scenario: Manual list, Cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Manual list content" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Manual list title |
      | field_c_p_list_column_count | 4                        |
      | field_c_p_list_fill_width   | 0                        |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 1                                  |
      | field_c_p_summary | Card summary 1                                |
      | field_c_p_link    | 0: Test link 1 - 1: https://example.com/link1 |
      | field_c_p_image   | [TEST] CivicTheme Image                       |
      | field_c_p_theme   | light                                         |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 2                                  |
      | field_c_p_summary | Card summary 2                                |
      | field_c_p_link    | 0: Test link 2 - 1: https://example.com/link2 |
      | field_c_p_image   | [TEST] CivicTheme Image                       |
      | field_c_p_theme   | light                                         |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 3                                  |
      | field_c_p_summary | Card summary 3                                |
      | field_c_p_link    | 0: Test link 3 - 1: https://example.com/link3 |
      | field_c_p_image   | [TEST] CivicTheme Image                       |
      | field_c_p_theme   | dark                                          |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card" paragraph:
      | field_c_p_title   | Card title 4                                  |
      | field_c_p_summary | Card summary 4                                |
      | field_c_p_link    | 0: Test link 4 - 1: https://example.com/link4 |
      | field_c_p_image   | [TEST] CivicTheme Image                       |
      | field_c_p_theme   | light                                         |

    When I visit "civictheme_page" "[TEST] Page Manual list content"
    Then I should see the text "[TEST] Manual list title"

    And I should see 4 ".ct-promo-card" elements
    And I should see 3 ".ct-promo-card.ct-theme-light" element
    And I should see 1 ".ct-promo-card.ct-theme-dark" elements

    And I should see the text "Card title 1"
    And I should see the text "Card summary 1"
    And the response should contain "https://example.com/link1"
    And I should see the text "Card title 2"
    And I should see the text "Card summary 2"
    And the response should contain "https://example.com/link2"
    And I should see the text "Card title 3"
    And I should see the text "Card summary 3"
    And the response should contain "https://example.com/link3"
    And I should see the text "Card title 4"
    And I should see the text "Card summary 4"
    And the response should contain "https://example.com/link4"

  @api
  Scenario: Manual list, Reference cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Manual list content" has "civictheme_manual_list" paragraph:
      | field_c_p_title             | [TEST] Manual list title |
      | field_c_p_list_column_count | 3                        |
      | field_c_p_list_fill_width   | 0                        |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_event_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Event |
      | field_c_p_theme     | light                   |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page |
      | field_c_p_theme     | light                   |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page |
      | field_c_p_theme     | dark                   |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Manual list title" has "civictheme_promo_card_ref" paragraph:
      | field_c_p_reference | [TEST] Referenced Page |
      | field_c_p_theme     | light                  |

    When I visit "civictheme_page" "[TEST] Page Manual list content"
    Then I should see the text "[TEST] Manual list title"

    And I should see 1 ".ct-event-card" elements
    And I should see 1 ".ct-event-card.ct-theme-light" elements
    And I should see 1 ".ct-subject-card" elements
    And I should see 1 ".ct-subject-card.ct-theme-light" elements
    And I should see 1 ".ct-navigation-card" elements
    And I should see 1 ".ct-navigation-card.ct-theme-dark" elements
    And I should see 1 ".ct-promo-card" elements
    And I should see 1 ".ct-promo-card.ct-theme-light" elements

    And I should see the text "[TEST] Referenced Event"
    And I should see the text "[TEST] Referenced Page"
