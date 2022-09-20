@civictheme @paragraph @civictheme_automated_list
Feature: View of Page content with Listing component

  Ensure that Page content can be viewed correctly with Listing component.

  Background:
    Given "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |
      | [TEST] Topic 4 |

    And "civictheme_page" content:
      | title                         | created                | status | field_c_n_topics                               |
      | Page Automated list component |                        | 1      |                                                |
      | [TEST] Page 1                 | [relative:-1 minutes]  | 1      | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 |
      | [TEST] Page 2                 | [relative:-2 minutes]  | 1      | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 |
      | [TEST] Page 3                 | [relative:-3 minutes]  | 1      | [TEST] Topic 1                                 |
      | [TEST] Page 4                 | [relative:-4 minutes]  | 1      | [TEST] Topic 1                                 |
      | [TEST] Page 5                 | [relative:-5 minutes]  | 1      | [TEST] Topic 2                                 |
      | [TEST] Page 6                 | [relative:-6 minutes]  | 1      | [TEST] Topic 2                                 |
      | [TEST] Page 7                 | [relative:-7 minutes]  | 1      | [TEST] Topic 3                                 |
      | [TEST] Page 8                 | [relative:-8 minutes]  | 1      | [TEST] Topic 3                                 |
      | [TEST] Page 9                 | [relative:-9 minutes]  | 1      |                                                |
      | [TEST] Page 10                | [relative:-10 minutes] | 1      |                                                |
      | [TEST] Page 11                | [relative:-11 minutes] | 0      |                                                |
      | [TEST] Page 12                | [relative:-12 minutes] | 0      | [TEST] Topic 3                                 |
      | [TEST] Page 13                | [relative:-13 minutes] | 1      |                                                |
      | [TEST] Page 14                | [relative:-14 minutes] | 1      |                                                |
      | [TEST] Page 15                | [relative:-15 minutes] | 1      |                                                |

  @api @testmode
  Scenario: Listing, defaults
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_content_type | civictheme_page |

    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 12 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element

    And I should not see an ".ct-list__filters .views-exposed-form" element

  @api @testmode
  Scenario: Listing, custom values
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_content_type | civictheme_page                              |
      | field_c_p_list_topic        |                                              |
      | field_c_p_list_site_section |                                              |
      | field_c_p_list_limit_type   | unlimited                                    |
      | field_c_p_list_limit        | 0                                            |
      | field_c_p_list_show_filters | 0                                            |
      | field_c_p_list_filters_exp  | 0                                            |
      # Fields.
      | field_c_p_title             | [TEST] Listing component title               |
      | field_c_p_list_link_above   | 0: Link above - 1: https://example.com/above |
      | field_c_p_list_link_below   | 0: Link below - 1: https://example.com/below |
      # Appearance.
      | field_c_p_theme             | dark                                         |
      | field_c_p_vertical_spacing  | bottom                                       |
      | field_c_p_background        | 1                                            |
      | field_c_p_list_item_view_as | civictheme_promo_card                        |
      | field_c_p_list_item_theme   | dark                                         |

    When I visit "civictheme_page" "Page Automated list component"
    Then I should see the text "[TEST] Listing component title"
    And I should see the link "Link above" with "https://example.com/above" in ".ct-list__header-link-above"
    And I should see the link "Link below" with "https://example.com/below" in ".ct-list__link-below"

    And I should see an ".ct-list" element
    And I should see an ".ct-list.ct-theme-dark" element
    And I should see an ".ct-list.ct-list--with-background" element
    And I should see an ".ct-list.ct-vertical-spacing-inset--bottom" element
    And I should see an ".ct-list .ct-item-grid__items" element

    And I should see 12 ".ct-item-grid__item" elements
    And I should see 12 ".ct-item-grid__item .ct-promo-card.ct-theme-dark" elements
    And I should see an ".ct-list__results-below .ct-pager" element
    And I should not see an ".ct-list__filters .views-exposed-form" element

  @api @testmode
  Scenario: Listing, unlimited
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_limit_type | unlimited |
      | field_c_p_list_limit      | 0         |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 12 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element

  @api @testmode
  Scenario: Listing, unlimited with limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_limit_type | unlimited |
      | field_c_p_list_limit      | 6         |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 6 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element

  @api @testmode
  Scenario: Listing, unlimited with limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_limit_type | limited |
      | field_c_p_list_limit      | 5       |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 5 ".ct-item-grid__item" elements
    And I should not see an ".ct-list__results-below .ct-pager" element

  @api @testmode
  Scenario: Listing, limited with limit more than page
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_limit_type | limited |
      | field_c_p_list_limit      | 14      |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 14 ".ct-item-grid__item" elements
    And I should not see an ".ct-list__results-below .ct-pager" element

  @api @testmode
  Scenario: Listing, topics
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_topics | [TEST] Topic 1, [TEST] Topic 2 |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 6 ".ct-item-grid__item" elements
    And I should not see an ".ct-list__results-below .ct-pager" element

  @api @testmode
  Scenario: Listing, exposed filters
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_show_filters | 1 |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 12 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element

    And I should see an ".ct-list__filters .views-exposed-form" element

  @api @javascript @skipped
  Scenario: Listing, exposed filters, filtering
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_show_filters | 1                  |
      | field_c_p_list_filters_exp  | type, topic, title |

    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 12 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element
    And I should see an ".ct-list__filters .views-exposed-form" element

    And I press the "Topics" button
    And I check the box "[TEST] Topic 2"
    And I press the "Topics" button
    And I wait 1 second
    And I wait for AJAX to finish

    And I press the "edit-submit-civictheme-automated-list" button
    And I should see 4 ".ct-item-grid__item" elements

    And I press the "Clear all" button
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see 12 ".ct-item-grid__item" elements

  @api @testmode
  Scenario: CivicTheme listing page with different view from listing type field.
    Given "civictheme_page" content:
      | title          | created            | status |
      | [TEST] Page 16 | [relative:-5 days] | 1      |
      | [TEST] Page 17 | [relative:-5 days] | 1      |

    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      # Selection.
      | field_c_p_list_limit_type   | unlimited |
      | field_c_p_list_limit        | 6         |
      | field_c_p_list_show_filters | 1         |
      | field_c_p_list_filters_exp  | title     |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 6 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element

    And I am logged in as a user with the "Administrator" role
    And I go to "admin/structure/paragraphs_type/civictheme_automated_list/fields/paragraph.civictheme_automated_list.field_c_p_list_type/storage"
    And I fill in "Allowed values list" with:
      """
      civictheme_automated_list__block1|Default Listing
      civictheme_automated_list_test__block_test_1|Test Listing
      """
    And I press "Save field settings"

    And I edit civictheme_page "Page Automated list component"
    And I press "field_c_n_components_0_edit"
    When I select "Test Listing" from "edit-field-c-n-components-0-subform-field-c-p-list-type"

    And I press "Save"

    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 2 ".ct-item-grid__item" elements
    And I should not see an ".ct-list__results-below .ct-pager" element

    And I see field "Title"
    And should see an "input[name='title']" element
    And should not see an "input[name='title'].required" element


  @api @testmode
  Scenario: CivicTheme listing page with pagination works as expected
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "Page Automated list component" has "civictheme_automated_list" paragraph:
      | field_c_p_list_show_filters | 0         |
      | field_c_p_list_limit_type   | unlimited |
      | field_c_p_list_limit        | 2         |
    When I visit "civictheme_page" "Page Automated list component"
    Then I should see an ".ct-list .ct-item-grid__items" element
    And I should see 2 ".ct-item-grid__item" elements
    And I should see an ".ct-list__results-below .ct-pager" element
    And I should not see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Previous" in the ".ct-list__results-below .ct-pager .ct-input--disabled.ct-pager__link" element
    And I should see "Last" in the ".ct-list__results-below .ct-pager" element
    And I click "2"
    And I should see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see "First" in the ".ct-list__results-below .ct-pager" element
    And I click "Last"
    And I should not see "Last" in the ".ct-list__results-below .ct-pager" element
    And I should see "First" in the ".ct-list__results-below .ct-pager" element
    And I should see "Next" in the ".ct-list__results-below .ct-pager .ct-input--disabled.ct-pager__link" element
