@p1 @civictheme @civictheme_automated_list
Feature: Automated list render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name           | field_c_m_image | moderation_state | status |
      | [TEST] Image 1 | test_image.jpg  | published        | 1      |

    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |
      | [TEST] Topic 4 |

    And "civictheme_page" content:
      | title                                 | created                | status | field_c_n_topics                               | moderation_state |
      | Test page with Automated list content |                        | 1      |                                                | published        |
      | [TEST] Page 1                         | [relative:-1 minutes]  | 1      | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 | published        |
      | [TEST] Page 2                         | [relative:-2 minutes]  | 1      | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 | published        |
      | [TEST] Page 3                         | [relative:-3 minutes]  | 1      | [TEST] Topic 1                                 | published        |
      | [TEST] Page 4                         | [relative:-4 minutes]  | 1      | [TEST] Topic 1                                 | published        |
      | [TEST] Page 5                         | [relative:-5 minutes]  | 1      | [TEST] Topic 2                                 | published        |
      | [TEST] Page 6                         | [relative:-6 minutes]  | 1      | [TEST] Topic 2                                 | published        |
      | [TEST] Page 7                         | [relative:-7 minutes]  | 1      | [TEST] Topic 3                                 | published        |
      | [TEST] Page 8                         | [relative:-8 minutes]  | 1      | [TEST] Topic 3                                 | published        |
      | [TEST] Page 9                         | [relative:-9 minutes]  | 1      |                                                | published        |
      | [TEST] Page 10                        | [relative:-10 minutes] | 1      |                                                | published        |
      | [TEST] Page 11                        | [relative:-11 minutes] | 0      |                                                | draft            |
      | [TEST] Page 12                        | [relative:-12 minutes] | 0      | [TEST] Topic 3                                 | draft            |
      | [TEST] Page 13                        | [relative:-13 minutes] | 1      |                                                | published        |
      | [TEST] Page 14                        | [relative:-14 minutes] | 1      |                                                | published        |
      | [TEST] Page 15                        | [relative:-15 minutes] | 1      |                                                | published        |

    And "civictheme_event" content:
      | title                     | created                | status | field_c_n_summary | field_c_n_thumbnail | field_c_n_topics               | field_c_n_date_range:value | field_c_n_date_range:end_value | moderation_state |
      | [TEST] Referenced Event 1 | [relative:-16 minutes] | 1      | Summary 1         | [TEST] Image 1      | [TEST] Topic 1, [TEST] Topic 2 | 2022-07-01T09:45:00        | 2022-08-14T11:30:00            | published        |
      | [TEST] Referenced Event 2 | [relative:-17 minutes] | 1      | Summary 2         | [TEST] Image 1      | [TEST] Topic 3, [TEST] Topic 4 | 2023-07-01T09:45:00        | 2023-08-14T11:30:00            | published        |
      | [TEST] Referenced Event 3 | [relative:-18 minutes] | 1      | Summary 3         | [TEST] Image 1      | [TEST] Topic 3, [TEST] Topic 4 | 2024-07-01T09:45:00        | 2025-08-14T11:30:00            | published        |

  @api @testmode
  Scenario: Automated list, All results (limited to a large number to fit on the page)
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title             | [TEST] Automated list title                         |
      | field_c_p_content:value     | [TEST] Automated list content <h2>subtitle</h2>     |
      | field_c_p_content:format    | civictheme_rich_text                                |
      | field_c_p_list_link_above   | 0: [TEST] Link above - 1: https://example.com/above |
      | field_c_p_list_link_below   | 0: [TEST] Link below - 1: https://example.com/below |
      | field_c_p_list_column_count | 4                                                   |
      | field_c_p_list_fill_width   | 0                                                   |
      | field_c_p_theme             | light                                               |
      | field_c_p_background        | 1                                                   |
      | field_c_p_vertical_spacing  | both                                                |
      # Limiting to a number much larger that a total number of test nodes to test without a pager.
      | field_c_p_list_limit_type   | limited                                             |
      | field_c_p_list_limit        | 96                                                  |

    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And I should see a ".ct-list.ct-list--with-background" element
    And I should see a ".ct-list.ct-vertical-spacing-inset--both" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"
    And I should see the text "[TEST] Automated list content"
    And the response should contain "[TEST] Automated list content <h2>subtitle</h2>"
    And I should see the link "[TEST] Link above" with "https://example.com/above" in '.ct-list'
    And I should see the link "[TEST] Link below" with "https://example.com/below" in '.ct-list'

    # 'Show x of y' is not visible.
    And I should not see an ".ct-list__results-count" element

    And I should see a ".ct-list__rows" element
    # 16 items = 15 pages - 2 pages unpublished + 3 events
    And I should see 16 ".ct-item-grid__item" elements
    And I should see 13 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should see 3 ".ct-item-grid__item .ct-event-card.ct-theme-light" elements
    And I should not see an ".ct-list__filters" element
    # Pager is not visible since there is not enough items (less than a set limit of 96) on the page.
    And I should not see an ".ct-list__pager" element

  @api @testmode
  Scenario: Automated list, unlimited with no limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title           | [TEST] Automated list title |
      | field_c_p_list_limit_type | unlimited                   |
      | field_c_p_list_limit      | 0                           |
    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    And I should see 12 ".ct-item-grid__item" elements
    And I should see 12 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should not see an ".ct-list__filters" element

    # 'Show x of y' is visible.
    And I should see an ".ct-list__results-count" element

    # Pager is visible and set to a number of pages in the view.
    And I should see an ".ct-list__pager" element
    # Items per page is visible.
    And I should see an ".ct-list__pager .ct-pager__items_per_page" element
    # Go to a second page and assert that the content is still present there (pager did not interfere with the pagination links).
    When I click "2"
    Then I should see 4 ".ct-item-grid__item" elements

  @api @testmode
  Scenario: Automated list, unlimited with limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title           | [TEST] Automated list title |
      | field_c_p_list_limit_type | unlimited                   |
      | field_c_p_list_limit      | 6                           |
    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    And I should see 6 ".ct-item-grid__item" elements
    And I should see 6 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should not see an ".ct-list__filters" element

    # 'Show x of y' is visible.
    And I should see an ".ct-list__results-count" element

    # Pager is visible and set to a custom number of pages.
    And I should see an ".ct-list__pager" element
    # Items per page is not visible since it is a custom number not from the list of available 'items per page' values.
    And I should not see an ".ct-list__pager .ct-pager__items_per_page" element
    # Go to a second page and assert that the content is still present there (pager did not interfere with the pagination links).
    When I click "2"
    Then I should see 6 ".ct-item-grid__item" elements

  @api @testmode
  Scenario: Automated list, limited with limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title           | [TEST] Automated list title |
      | field_c_p_list_limit_type | limited                     |
      | field_c_p_list_limit      | 6                           |
    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    # 'Show x of y' is not visible.
    And I should not see an ".ct-list__results-count" element

    And I should see 6 ".ct-item-grid__item" elements
    And I should see 6 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should not see an ".ct-list__filters" element
    # Pager is not visible since there is a hard limit of 6 items was set.
    And I should not see an ".ct-list__pager" element

  @api @testmode
  Scenario: Automated list, topics
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title       | [TEST] Automated list title    |
      | field_c_p_list_topics | [TEST] Topic 1, [TEST] Topic 2 |
    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    And I should see 7 ".ct-item-grid__item" elements
    And I should see 6 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should see 1 ".ct-item-grid__item .ct-event-card.ct-theme-light" elements
    And I should not see an ".ct-list__pager" element
    And I should not see an ".ct-list__filters" element

  @api @testmode
  Scenario: Automated list, pagination
    # Additional testing of links within pagination.
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title           | [TEST] Automated list title |
      | field_c_p_list_limit_type | unlimited                   |
      | field_c_p_list_limit      | 2                           |
    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    And I should see 2 ".ct-item-grid__item" elements
    And I should see 2 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should see an ".ct-list__pager" element
    # Items per page is not visible since it is a custom number not from the list of available 'items per page' values.
    And I should not see an ".ct-list__pager .ct-pager__items_per_page" element

    And I should not see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Previous" in the ".ct-list__results-below .ct-pager .ct-pager__link[disabled]" element
    And I should see "Last" in the ".ct-list__results-below .ct-pager" element

    When I click "2"
    Then I should see 2 ".ct-item-grid__item" elements
    And I should see 2 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should see an ".ct-list__pager" element
    # Items per page is not visible since it is a custom number not from the list of available 'items per page' values.
    And I should not see an ".ct-list__pager .ct-pager__items_per_page" element
    And I should see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see "First" in the ".ct-list__results-below .ct-pager" element

    When I click "Last"
    And I should not see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Next" in the ".ct-list__results-below .ct-pager .ct-pager__link[disabled]" element

  @api @testmode
  Scenario: Automated list, Event
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title             | [TEST] Automated list title |
      | field_c_p_list_content_type | civictheme_event            |
    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    And I should see 3 ".ct-item-grid__item" elements
    And I should see 3 ".ct-item-grid__item .ct-event-card.ct-theme-light" elements
    And I should not see an ".ct-list__pager" element
    And I should not see an ".ct-list__filters" element

  @api @testmode
  Scenario: Automated list, different view from listing type field
    Given "civictheme_page" content:
      | title          | created            | status | moderation_state |
      | [TEST] Page 16 | [relative:-5 days] | 1      | published        |
      | [TEST] Page 17 | [relative:-5 days] | 1      | published        |

    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title            | [TEST] Automated list title |
      | field_c_p_list_limit_type  | unlimited                   |
      | field_c_p_list_limit       | 6                           |
      | field_c_p_list_filters_exp | title                       |

    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And I should see the text "[TEST] Automated list title"

    And I should see 6 ".ct-item-grid__item" elements
    And I should see 6 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should see an ".ct-list__pager" element
    And I should see an ".ct-list__filters" element

    # Add a Test view as a list type.
    # This view only shows items older than 2 days and has a Title filter exposed.
    When I am logged in as a user with the "Administrator" role
    And I go to "admin/structure/paragraphs_type/civictheme_automated_list/fields/paragraph.civictheme_automated_list.field_c_p_list_type/storage"
    And I fill in "Allowed values list" with:
      """
      civictheme_automated_list__block1|Default
      civictheme_automated_list_test__block_test_1|Test
      """
    And I press "Save field settings"

    # Select newly added list type Test.
    And I edit civictheme_page "Test page with Automated list content"
    And I press "field_c_n_components_0_edit"
    And I select "Test" from "edit-field-c-n-components-0-subform-field-c-p-list-type"
    And I press "Save"

    And I should see 2 ".ct-item-grid__item" elements
    And I should see 2 ".ct-item-grid__item .ct-promo-card.ct-theme-light" elements
    And I should not see an ".ct-list__pager" element

    And I should see an ".ct-list__filters" element
    And I see field "Title"
    And should see an "input[name='title']" element
    And should not see an "input[name='title'].required" element

  @api
  Scenario: Automated list, all filters shown
    Given "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title            | [TEST] Automated list title |
      | field_c_p_list_limit_type  | unlimited                   |
      | field_c_p_list_filters_exp | title, topic, type          |

    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see an ".ct-list__filters" element
    And I should see an ".ct-group-filter__filters .ct-form-element--topic" element
    And I should see an ".ct-group-filter__filters .ct-form-element--type" element
    And I should see an ".ct-group-filter__filters .ct-form-element--title" element

  @api
  Scenario: Automated list, no filters shown
    Given "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title            | [TEST] Automated list title |
      | field_c_p_list_limit_type  | unlimited                   |
      | field_c_p_list_filters_exp |                             |

    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should not see an ".ct-list__filters" element
    And I should not see an ".ct-group-filter__filters .ct-form-element--topic" element
    And I should not see an ".ct-group-filter__filters .ct-form-element--type" element
    And I should not see an ".ct-group-filter__filters .ct-form-element--title" element

  @api
  Scenario: Automated list, some filters shown
    Given "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list content" has "civictheme_automated_list" paragraph:
      | field_c_p_title            | [TEST] Automated list title |
      | field_c_p_list_limit_type  | unlimited                   |
      | field_c_p_list_filters_exp | title, topic                |

    When I visit "civictheme_page" "Test page with Automated list content"
    Then I should see an ".ct-list__filters" element
    And I should see an ".ct-group-filter__filters .ct-form-element--topic" element
    And I should not see an ".ct-group-filter__filters .ct-form-element--type" element
    And I should see an ".ct-group-filter__filters .ct-form-element--title" element

  @api
  Scenario: Automated list, is not self referenced
    Given "civictheme_page" content:
      | title                                             | status | moderation_state |
      | Test page content in list                         | 1      | published        |
      | Test page with Automated list non self referenced | 1      | published        |

    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Test page with Automated list non self referenced" has "civictheme_automated_list" paragraph:
      | field_c_p_title           | [TEST] Automated list title |
      | field_c_p_list_limit_type | unlimited                   |
      | field_c_p_list_limit      | 0                           |

    And I am an anonymous user
    When I visit "civictheme_page" "Test page with Automated list non self referenced"
    Then I should see a ".ct-list" element
    And I should see a ".ct-list.ct-theme-light" element
    And the response should contain "ct-automated-list-"
    And the ".ct-list .ct-promo-card__title__link" element should contain "Test page content in list"
    And the ".ct-list .ct-promo-card__title__link" element should not contain "Test page with Automated list non self referenced"
