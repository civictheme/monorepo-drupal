@p0 @civictheme @civictheme_card @civictheme_service_card
Feature: Service card render

  Background:
    And "civictheme_page" content:
      | title                          | status | field_c_n_site_section |
      | [TEST] Page Service cards test | 1      |                        |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Service cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Service cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Service manual list                         |
      | field_c_p_column_count    | 3                                                  |
      | field_c_p_list_link_above | 0: View all Service cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Service manual list" has "civictheme_service_card" paragraph:
      | field_c_p_title | Service card title 1                                                              |
      | field_c_p_links | 0: Test link 1 - 1: https://example.com, 0: Test link 11 - 1: https://example.com |
      | field_c_p_theme | light                                                                             |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Service manual list" has "civictheme_service_card" paragraph:
      | field_c_p_title | Service card title 2                                                              |
      | field_c_p_links | 0: Test link 2 - 1: https://example.com, 0: Test link 21 - 1: https://example.com |
      | field_c_p_theme | dark                                                                              |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Service manual list" has "civictheme_service_card" paragraph:
      | field_c_p_title | Service card title 3                                                                                                                                                  |
      | field_c_p_links | 0: Test link 3 - 1: https://example.com, 0: Test link 31 - 1: https://example.com, 0: Test link 32 - 1: https://example.com, 0: Test link 33 - 1: https://example.com |
      | field_c_p_theme | dark                                                                                                                                                                  |

    When I visit "civictheme_page" "[TEST] Page Service cards test"
    And I should see the text "[TEST] Service manual list"
    Then I should see the link "View all Service cards" with "https://example.com" in '.ct-list'
    And I should see 1 ".ct-list" elements
    And I should see 3 ".ct-service-card" elements
    And I should see 2 ".ct-service-card.ct-theme-dark" elements
    And I should see 1 ".ct-service-card.ct-theme-light" elements
    And I should see 3 ".ct-service-card__title" elements
    And I should see 3 ".ct-service-card__links" elements
    And I should not see an ".ct-subject-card" element
    And I should not see an ".ct-service-card img" element
    And I should see the text "Service card title 1"
    And I should see the text "Service card title 2"
    And I should see the text "Service card title 3"
    And I should see the text "Test link 1"
    And I should see the text "Test link 2"
    And I should see the text "Test link 3"
