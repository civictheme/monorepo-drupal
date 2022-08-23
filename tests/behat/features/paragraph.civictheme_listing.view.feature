@civictheme @paragraph @civictheme_listing
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
      | [TEST] Page Listing component |                        | 1      |                                                |
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
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_content_type | civictheme_page |

    When I visit "civictheme_page" "[TEST] Page Listing component"

    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 12 ".civictheme-card-container__card" elements
    And I should see an ".civictheme-listing__results-below .civictheme-pager" element

    And I should not see an ".civictheme-listing__filters .views-exposed-form" element

  @api @testmode
  Scenario: Listing, custom values
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_content_type | civictheme_page                              |
      | field_c_p_listing_topic        |                                              |
      | field_c_p_listing_site_section |                                              |
      | field_c_p_listing_limit_type   | unlimited                                    |
      | field_c_p_listing_limit        | 0                                            |
      | field_c_p_listing_show_filters | 0                                            |
      | field_c_p_listing_filters_exp  | 0                                            |
      # Fields.
      | field_c_p_title                | [TEST] Listing component title               |
      | field_c_p_listing_link_above   | 0: Link above - 1: https://example.com/above |
      | field_c_p_listing_link_below   | 0: Link below - 1: https://example.com/below |
      # Appearance.
      | field_c_p_theme                | dark                                         |
      | field_c_p_space                | bottom                                       |
      | field_c_p_background           | 1                                            |
      | field_c_p_listing_item_view_as | civictheme_promo_card                        |
      | field_c_p_listing_item_theme   | dark                                         |

    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see the text "[TEST] Listing component title"
    And I should see the link "Link above" with "https://example.com/above" in ".civictheme-listing__header-link-above"
    And I should see the link "Link below" with "https://example.com/below" in ".civictheme-listing__link-below"

    And I should see an ".civictheme-listing" element
    And I should see an ".civictheme-listing.civictheme-theme-dark" element
    And I should see an ".civictheme-listing.civictheme-listing--with-background" element
    And I should see an ".civictheme-listing.civictheme-listing--vertical-space-bottom" element
    And I should see an ".civictheme-listing .civictheme-card-container__cards" element

    And I should see 12 ".civictheme-card-container__card" elements
    And I should see 12 ".civictheme-card-container__card .civictheme-promo-card.civictheme-theme-dark" elements
    And I should see an ".civictheme-listing__results-below .civictheme-pager" element

    And I should not see an ".civictheme-listing__filters .views-exposed-form" element

  @api @testmode
  Scenario: Listing, unlimited
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_limit_type | unlimited |
      | field_c_p_listing_limit      | 0         |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 12 ".civictheme-card-container__card" elements
    And I should see an ".civictheme-listing__results-below .civictheme-pager" element

  @api @testmode
  Scenario: Listing, unlimited with limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_limit_type | unlimited |
      | field_c_p_listing_limit      | 6         |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 6 ".civictheme-card-container__card" elements
    And I should see an ".civictheme-listing__results-below .civictheme-pager" element

  @api @testmode
  Scenario: Listing, unlimited with limit
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_limit_type | limited |
      | field_c_p_listing_limit      | 5       |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 5 ".civictheme-card-container__card" elements
    And I should not see an ".civictheme-listing__results-below .civictheme-pager" element

  @api @testmode
  Scenario: Listing, limited with limit more than page
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_limit_type | limited |
      | field_c_p_listing_limit      | 14      |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 14 ".civictheme-card-container__card" elements
    And I should not see an ".civictheme-listing__results-below .civictheme-pager" element

  @api @testmode
  Scenario: Listing, topics
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_topics | [TEST] Topic 1, [TEST] Topic 2 |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 6 ".civictheme-card-container__card" elements
    And I should not see an ".civictheme-listing__results-below .civictheme-pager" element

  @api @testmode
  Scenario: Listing, exposed filters
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_show_filters | 1 |
    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 12 ".civictheme-card-container__card" elements
    And I should see an ".civictheme-listing__results-below .civictheme-pager" element

    And I should see an ".civictheme-listing__filters .views-exposed-form" element

  @api @javascript
  Scenario: Listing, exposed filters, filtering
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Listing component" has "civictheme_listing" paragraph:
      # Selection.
      | field_c_p_listing_show_filters | 1 |

    When I visit "civictheme_page" "[TEST] Page Listing component"
    Then I should see an ".civictheme-listing .civictheme-card-container__cards" element
    And I should see 12 ".civictheme-card-container__card" elements
    And I should see an ".civictheme-listing__results-below .civictheme-pager" element

    And I should see an ".civictheme-listing__filters .views-exposed-form" element

    And I press the "Topics" button
    And I check the box "[TEST] Topic 2"
    And I press the "Topics" button
    And I wait 1 second
    And I wait for AJAX to finish

    And I press the "edit-submit-civictheme-listing" button
    And I should see 4 ".civictheme-card-container__card" elements

    And I press the "Clear all" button
    And I wait 1 second
    And I wait for AJAX to finish

    And I should see 12 ".civictheme-card-container__card" elements
