@civic @paragraph @civic_listing
Feature: Tests the Civic filtering system within blocks and view pages.

  Ensure that Listing paragraph exists and has the expected fields and the listing component can be viewed
  and filtered correctly.

  Background:
    Given "civic_topics" terms:
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
    Given "civic_page" content:
      | title                           | status | field_c_n_topics                               |
      | [TEST] Page Listing component   | 1      |                                                |
      | [TEST] All Topics               | 1      | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 |
      | [TEST] Topic 1 and Topic 2 Page | 1      | [TEST] Topic 1, [TEST] Topic 2                 |
      | [TEST] Topic 1 Page             | 1      | [TEST] Topic 1                                 |
      | [TEST] Topic 2 Page             | 1      | [TEST] Topic 2                                 |
      | [TEST] Topic 3 Page             | 1      | [TEST] Topic 3                                 |
      | [TEST] No Topics Page           | 1      |                                                |
      | [TEST] Unpublished page         | 0      |                                                |

  @api
  Scenario: Civic page content type page can be viewed by anonymous with listing
    Given I am an anonymous user
    And "field_c_n_components" in "civic_page" "node" with "title" of "[TEST] Page Listing component" has "civic_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civic_page                                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | limited                                    |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 0                                          |
      | field_c_p_show_pager           | 0                                          |
    When I visit "civic_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component "
    Then I should see the link "View all pages" with "https://example.com" in 'div.civic-listing__cta'
    And I should see an "div.civic-listing .civic-card-container__cards" element
    And I should see 6 "div.civic-card-container__card" elements
    And I should not see an "div.civic-listing__body .civic-pager" element
    And I should not see an "div.civic-listing__body .views-exposed-form" element

  @api @javascript
  Scenario: Civic listing component should filter pages, update selected filters correctly.
    Given I am an anonymous user
    And "field_c_n_components" in "civic_page" "node" with "title" of "[TEST] Page Listing component" has "civic_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civic_page                                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 0                                          |
      | field_c_p_limit_type           | unlimited                                  |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 1                                          |
      | field_c_p_show_pager           | 0                                          |
    When I visit "civic_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component "
    Then I should see the link "View all pages" with "https://example.com" in 'div.civic-listing__cta'
    And I should see an "div.civic-listing .civic-card-container__cards" element
    And I should see an "div.civic-listing__body .views-exposed-form" element
    # Test JS filtering and the loaded view results.
    And I press the "Content type" button
    And I select the radio button "Page"
    And I press the "Content type" button
    And I press the "Apply" button
    And I should see "Page" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I should see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should not see "[TEST] Unpublished page" in the ".civic-card-container" element
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
    And I should see "Page" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] Topic 1" in the ".civic-large-filter__selected-filters" element
    And I press the "Apply" button
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I press the "Topics" button
    And I check the box "[TEST] Topic 2"
    And I press the "Topics" button
    And I press the "Apply" button
    And I should see "Page" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] Topic 1" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] Topic 2" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I press the "Apply" button
    # Test clear all filter button
    And I press the "Clear all" button
    And I should not see "Page" in the ".civic-large-filter__selected-filters" element
    And I should not see "[TEST] Topic 1" in the ".civic-large-filter__selected-filters" element
    And I should not see "[TEST] Topic 2" in the ".civic-large-filter__selected-filters" element
    And I should see "Any" in the ".civic-large-filter__selected-filters" element
    And I should not see "[TEST] Unpublished page" in the ".civic-card-container" element
    And I should see "[TEST] No Topics Page" in the ".civic-card-container" element

  @api @javascript
  Scenario: Civic listing page with large filter should filter pages, update selected filters correctly.
    Given I am an anonymous user
    When I visit "/civic/listing"
    And I should see an ".civic-card-container__cards" element
    And I should see an ".views-exposed-form" element
    # Test JS filtering and the loaded view results.
    And I press the "Content type" button
    And I select the radio button "Page"
    And I press the "Content type" button
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "Page" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should not see "[TEST] Unpublished page" in the ".civic-card-container" element
    And I press the "Topics" button
    And I check the box "[TEST] Topic 1"
    And I press the "Topics" button
    And I should see "Page" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] Topic 1" in the ".civic-large-filter__selected-filters" element
    And I wait 1 seconds
    And I wait for AJAX to finish
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I press the "Topics" button
    And I check the box "[TEST] Topic 2"
    And I press the "Topics" button
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "Page" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] Topic 1" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] Topic 2" in the ".civic-large-filter__selected-filters" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I wait for AJAX to finish
    # Test clear all filter button
    And I press the "Clear all" button
    And I wait 1 second
    And I wait for AJAX to finish
    And I should not see "Page" in the ".civic-large-filter__selected-filters" element
    And I should not see "[TEST] Topic 1" in the ".civic-large-filter__selected-filters" element
    And I should not see "[TEST] Topic 2" in the ".civic-large-filter__selected-filters" element
    And I should see "Any" in the ".civic-large-filter__selected-filters" element
    And I should not see "[TEST] Unpublished page" in the ".civic-card-container" element
    And I should see "[TEST] No Topics Page" in the ".civic-card-container" element

  @api @javascript
  Scenario: Civic listing page with basic filter should filter pages, update selected filters correctly.
    Given I am an anonymous user
    When I visit "/civic/listing-basic"
    And I should see an ".civic-card-container__cards" element
    And I should see an ".views-exposed-form" element
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] No Topics Page" in the ".civic-card-container" element
    # Filtering by [TEST] Topic 1
    And I select the filter chip "[TEST] Topic 1"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    # Filtering by [TEST] Topic 3
    And I select the filter chip "[TEST] Topic 3"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    # Filtering by [TEST] Topic 2
    And I select the filter chip "[TEST] Topic 2"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element

  @api @javascript
  Scenario: Civic listing page with basic multiple value filter should filter pages, update selected filters correctly.
    Given I am an anonymous user
    When I visit "/civic/listing-basic-multi"
    And I should see an ".civic-card-container__cards" element
    And I should see an ".views-exposed-form" element
    # Filtering by [TEST] Topic 1.
    And I check the filter chip "[TEST] Topic 1"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    # Filtering by [TEST] Topic 1 or [TEST] Topic 3.
    And I check the filter chip "[TEST] Topic 3"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    # Filtering by [TEST] Topic 1, [TEST] Topic 2 or [TEST] Topic 3.
    And I check the filter chip "[TEST] Topic 2"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 3 Page" in the ".civic-card-container" element
    And I should not see "[TEST] No Topics Page" in the ".civic-card-container" element
    # Filtering by [TEST] Topic 2.
    And I uncheck the filter chip "[TEST] Topic 1"
    And I uncheck the filter chip "[TEST] Topic 3"
    And I wait 1 second
    And I wait for AJAX to finish
    And I should see "[TEST] All Topics" in the ".civic-card-container" element
    And I should see "[TEST] Topic 1 and Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 1 Page" in the ".civic-card-container" element
    And I should see "[TEST] Topic 2 Page" in the ".civic-card-container" element
    And I should not see "[TEST] Topic 3 Page" in the ".civic-card-container" element
