@civictheme @paragraph @civictheme_listing
Feature: Tests the CivicTheme filtering system within blocks and view pages.

  Ensure that Listing paragraph exists and has the expected fields and the listing component can be viewed
  and filtered correctly.

  Background:
    Given "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |
      | [TEST] Topic 4 |
      | [TEST] Topic 5 |
      | [TEST] Topic 6 |
      | [TEST] Topic 7 |
      | [TEST] Topic 8 |
      | [TEST] Topic 9 |
    Given "civictheme_page" content:
      | title                           | status | field_c_n_topics                               |
      | [TEST] Page Listing component   | 1      |                                                |
      | [TEST] All Topics               | 1      | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 |
      | [TEST] Topic 1 and Topic 2 Page | 1      | [TEST] Topic 1, [TEST] Topic 2                 |
      | [TEST] Topic 1 Page             | 1      | [TEST] Topic 1                                 |
      | [TEST] Topic 1 Page Light       | 1      | [TEST] Topic 1                                 |
      | [TEST] Topic 1 Page Dark        | 1      | [TEST] Topic 1                                 |
      | [TEST] Topic 2 Page             | 1      | [TEST] Topic 2                                 |
      | [TEST] Topic 3 Page             | 1      | [TEST] Topic 3                                 |
      | [TEST] No Topics Page           | 1      |                                                |
      | [TEST] Unpublished page         | 0      |                                                |
      | [TEST] Page limited to 5 cards  | 1      |                                                |
      | [TEST] Page unlimited cards     | 1      |                                                |
      | [TEST] Page unlimited cards max | 1      |                                                |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with listing
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component"
    Then I should see the link "View all pages" with "https://example.com" in 'div.civictheme-listing__cta'
    And I should see an "div.civictheme-listing .civictheme-card-container__cards" element
    And I should see 6 "div.civictheme-card-container__card" elements
    And I should not see an "div.civictheme-listing__results-below .civictheme-pager" element
    And I should not see an "div.civictheme-listing__exposed-form .views-exposed-form" element

  @api @javascript
  Scenario: CivicTheme listing component should filter pages, update selected filters correctly.
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 0                                          |
      | field_c_p_limit_type           | unlimited                                  |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 1                                          |
      | field_c_p_show_pager           | 0                                          |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component"
    Then I should see the link "View all pages" with "https://example.com" in 'div.civictheme-listing__cta'
    And I should see an "div.civictheme-listing .civictheme-card-container__cards" element
    And I should see an "div.civictheme-listing__exposed-form .views-exposed-form" element
    # Test JS filtering and the loaded view results.
    And I press the "Content type" button
    And I wait 1 second
    And I select the radio button "Page"
    And I press the "Content type" button
    And I press the "edit-submit-civictheme-listing" button
    And I should see "Page" in the ".civictheme-large-filter__selected-filters" element
    And I should see "[TEST] All Topics" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 3 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] No Topics Page" in the ".civictheme-card-container" element
    And I should not see "[TEST] Unpublished page" in the ".civictheme-card-container" element
    # Test search dropdown filtering.
    And I press the "Topics" button
    And I wait 1 second
    And I fill in "Filter by Keyword" with "Topic 1"
    And I wait 1 second
    And I should see "[TEST] Topic 1" in the "#edit-topic" element
    And I should not see "[TEST] Topic 2" in the "#edit-topic" element
    And I should not see "[TEST] Topic 3" in the "#edit-topic" element
    And I check the box "[TEST] Topic 1"
    And I press the "Topics" button
    And I should see "Page" in the ".civictheme-large-filter__selected-filters" element
    And I should see "[TEST] Topic 1" in the ".civictheme-large-filter__selected-filters" element
    And I press the "edit-submit-civictheme-listing" button
    And I should not see "[TEST] No Topics Page" in the ".civictheme-card-container" element
    And I should see "[TEST] All Topics" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civictheme-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civictheme-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civictheme-card-container" element
    And I press the "Topics" button
    And I check the box "[TEST] Topic 2"
    And I press the "Topics" button
    And I press the "edit-submit-civictheme-listing" button
    And I should see "Page" in the ".civictheme-large-filter__selected-filters" element
    And I should see "[TEST] Topic 1" in the ".civictheme-large-filter__selected-filters" element
    And I should see "[TEST] Topic 2" in the ".civictheme-large-filter__selected-filters" element
    And I should see "[TEST] All Topics" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civictheme-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civictheme-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civictheme-card-container" element
    And I press the "edit-submit-civictheme-listing" button
    # Test clear all filter button
    And I press the "Clear all" button
    And I should not see "Page" in the ".civictheme-large-filter__selected-filters" element
    And I should not see "[TEST] Topic 1" in the ".civictheme-large-filter__selected-filters" element
    And I should not see "[TEST] Topic 2" in the ".civictheme-large-filter__selected-filters" element
    And I should see "Any" in the ".civictheme-large-filter__selected-filters" element
    And I should not see "[TEST] Unpublished page" in the ".civictheme-card-container" element
    And I should see "[TEST] No Topics Page" in the ".civictheme-card-container" element

  @api @javascript @civictheme_listing_theming
  Scenario: CivicTheme listing pages with different theming options should display correct classes on component.
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Topic 1 Page Light" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component Light             |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
      | field_c_p_theme                | light                                      |
      | field_c_p_card_theme           | dark                                       |
      | field_c_p_background           | 1                                          |
      | field_c_p_space                | both                                       |
    And I visit civictheme_page "[TEST] Topic 1 Page Light"
    And I should see an ".civictheme-listing.civictheme-theme-light" element
    And I should not see an ".civictheme-listing.civictheme-theme-dark" element
    And I should see an ".civictheme-listing.civictheme-listing--with-background" element
    And I should see an ".civictheme-listing.civictheme-listing--vertical-space-both" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-top" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-bottom" element
    And I should see an ".civictheme-promo-card.civictheme-theme-dark" element
    And I should not see an ".civictheme-promo-card.civictheme-theme-light" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Topic 1 Page Dark" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component 1 Dark            |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
      | field_c_p_theme                | dark                                       |
      | field_c_p_card_theme           | light                                      |
      | field_c_p_background           | 1                                          |
      | field_c_p_space                | both                                       |
    And I visit civictheme_page "[TEST] Topic 1 Page Dark"
    And I should see an ".civictheme-listing.civictheme-theme-dark" element
    And I should not see an ".civictheme-listing.civictheme-theme-light" element
    And I should see an ".civictheme-listing.civictheme-listing--with-background" element
    And I should see an ".civictheme-listing.civictheme-listing--vertical-space-both" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-top" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-bottom" element
    And I should see an ".civictheme-promo-card.civictheme-theme-light" element
    And I should not see an ".civictheme-promo-card.civictheme-theme-dark" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Topic 2 Page" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component 2                 |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
      | field_c_p_theme                | dark                                       |
      | field_c_p_card_theme           | light                                      |
      | field_c_p_background           | 0                                          |
      | field_c_p_space                | top                                        |
    And I visit civictheme_page "[TEST] Topic 2 Page"
    And I should see an ".civictheme-listing.civictheme-theme-dark" element
    And I should not see an ".civictheme-listing.civictheme-theme-light" element
    And I should not see an ".civictheme-listing.civictheme-listing--with-background" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-both" element
    And I should see an ".civictheme-listing.civictheme-listing--vertical-space-top" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-bottom" element
    And I should see an ".civictheme-listing .civictheme-promo-card.civictheme-theme-light" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Topic 3 Page" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component 3                 |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 0                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
      | field_c_p_theme                | dark                                       |
      | field_c_p_card_theme           | dark                                       |
      | field_c_p_background           | 1                                          |
      | field_c_p_space                | none                                       |
    And I visit civictheme_page "[TEST] Topic 3 Page"
    And I should see an ".civictheme-listing.civictheme-theme-dark" element
    And I should not see an ".civictheme-listing.civictheme-theme-light" element
    And I should see an ".civictheme-listing.civictheme-listing--with-background" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-both" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-top" element
    And I should not see an ".civictheme-listing.civictheme-listing--vertical-space-bottom" element
    And I should see an ".civictheme-listing .civictheme-promo-card.civictheme-theme-dark" element
    #Should only see 12 items and no pager
    And I should see an "div.civictheme-listing .civictheme-card-container__cards" element
    And I should see 12 "div.civictheme-card-container__card" elements
    And I should not see an "div.civictheme-listing__results-below .civictheme-pager" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page limited to 5 cards" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component 4                 |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 5                                          |
      | field_c_p_limit_type           | limited                                    |
    And I visit civictheme_page "[TEST] Page limited to 5 cards"
    And I should see an "div.civictheme-listing .civictheme-card-container__cards" element
    And I should see 5 "div.civictheme-card-container__card" elements
    And I should not see an "div.civictheme-listing__results-below .civictheme-pager" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page unlimited cards" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Page unlimited cards                  |
      | field_c_p_content_type         | civictheme_page                              |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com   |
      | field_c_p_view_as              | 0                                            |
      | field_c_p_listing_f_exposed    | 0                                            |
      | field_c_p_hide_count           | 0                                            |
      | field_c_p_listing_limit        | 0                                            |
      | field_c_p_limit_type           | unlimited                                    |
    And I visit civictheme_page "[TEST] Page unlimited cards"
    And I should see an "div.civictheme-listing .civictheme-card-container__cards" element
    And I should see 12 "div.civictheme-card-container__card" elements
    And I should see an "div.civictheme-listing__results-below .civictheme-pager" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page unlimited cards max" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Page unlimited cards max              |
      | field_c_p_content_type         | civictheme_page                              |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com   |
      | field_c_p_view_as              | 0                                            |
      | field_c_p_listing_f_exposed    | 0                                            |
      | field_c_p_hide_count           | 0                                            |
      | field_c_p_listing_limit        | 5                                            |
      | field_c_p_limit_type           | unlimited                                    |
    And I visit civictheme_page "[TEST] Page unlimited cards max"
    And I should see an "div.civictheme-listing .civictheme-card-container__cards" element
    And I should see 5 "div.civictheme-card-container__card" elements
    And I should see an "div.civictheme-listing__results-below .civictheme-pager" element

  @api
  Scenario: CivicTheme listing page with different view set and enforced by hook.
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civictheme_event                           |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component"
    And I should see an "[data-view='civictheme_listing_examples']" element

    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civictheme_page                            |
      | field_c_p_listing_type         | civictheme_listing__block1                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component"
    And I should see an "[data-view='civictheme_listing']" element

  @api @javascript
  Scenario: CivicTheme listing page with different view set and enforced by hook.
    Given I am logged in as a user with the "Administrator" role
    And I go to "admin/structure/paragraphs_type/civictheme_listing/fields/paragraph.civictheme_listing.field_c_p_listing_type/storage"
    And I fill in "Allowed values list" with:
      """
      civictheme_listing__block1|Default Listing
      civictheme_listing_examples__block1|Example Listing
      """
    And I press "Save field settings"
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civictheme_page                              |
      | field_c_p_listing_type         | civictheme_listing_examples__block1          |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com   |
      | field_c_p_view_as              | 0                                            |
      | field_c_p_listing_f_exposed    | 0                                            |
      | field_c_p_hide_count           | 0                                            |
      | field_c_p_listing_limit        | 5                                            |
      | field_c_p_limit_type           | unlimited                                    |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component"
    And I should see an "[data-view='civictheme_listing_examples']" element
    And I go to "admin/structure/paragraphs_type/civictheme_listing/fields/paragraph.civictheme_listing.field_c_p_listing_type/storage"
    And I fill in "Allowed values list" with:
      """
      civictheme_listing__block1|Default Listing
      """
    And I press "Save field settings"
