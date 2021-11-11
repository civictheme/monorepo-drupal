@civic @civic_listing_page
Feature: Tests the Listing component

  Ensure that Page content can be viewed correctly.

  Background:
    Given "civic_page" content:
      | title                         | status |
      | [TEST] Page Listing component | 1      |
      | [TEST] Page                   | 1      |
      | [TEST] Page 1                 | 1      |
      | [TEST] Page 2                 | 1      |
      | [TEST] Page 3                 | 1      |
      | [TEST] Page 4                 | 1      |
      | [TEST] Page 5                 | 1      |
      | [TEST] Page 6                 | 1      |
      | [TEST] Page 7                 | 1      |
      | [TEST] Page 8                 | 1      |
      | [TEST] Page 9                 | 1      |
      | [TEST] Page 10                | 1      |
      | [TEST] Page 11                | 1      |
      | [TEST] Page 12                | 1      |
      | [TEST] Page 13                | 1      |
      | [TEST] Page 14                | 1      |
      | [TEST] Page 15                | 1      |

    And "civic_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |
      | [TEST] Topic 3 |

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

  @api
  Scenario: Civic page content type page can be viewed by anonymous with listing, filters and pagination enabled
    Given I am an anonymous user
    And "field_c_n_components" in "civic_page" "node" with "title" of "[TEST] Page Listing component" has "civic_listing" paragraph:
      | field_c_p_title                | [TEST] Listing component                   |
      | field_c_p_content_type         | civic_page                                 |
      | field_c_p_read_more            | 0: View all pages - 1: https://example.com |
      | field_c_p_view_as              | 0                                          |
      | field_c_p_listing_f_exposed    | 0                                          |
      | field_c_p_hide_count           | 0                                          |
      | field_c_p_listing_limit        | 6                                          |
      | field_c_p_limit_type           | unlimited                                  |
      | field_c_p_listing_multi_select | 1                                          |
      | field_c_p_show_filters         | 1                                          |
      | field_c_p_show_pager           | 1                                          |
    When I visit "civic_page" "[TEST] Page Listing component"
    And I should see the text "[TEST] Listing component "
    Then I should see the link "View all pages" with "https://example.com" in 'div.civic-listing__cta'
    And I should see an "div.civic-listing .civic-card-container__cards" element
    And I should see 6 "div.civic-card-container__card" elements
    And I should see an "div.civic-listing__body .civic-pager" element
    And I should see an "div.civic-listing__body .views-exposed-form" element
