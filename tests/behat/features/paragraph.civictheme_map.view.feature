@civictheme @paragraph @civictheme_map
Feature: View of Page content with map component

  Ensure that Page content can be viewed correctly with map component.

  Background:
    Given "civictheme_page" content:
      | title                           | status |
      | [TEST] Page map test            | 1      |

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with map light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page map test" has "civictheme_map" paragraph:
      | field_c_p_address            | Australia                 |
      | field_c_p_theme              | light                     |
      | field_c_p_space              | both                      |
      | field_c_p_background         | 0                         |
      | field_c_p_embed_url          | 0: [TEST] link 1 - 1: https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed |
      | field_c_p_share_link         | 0: [TEST] link 1 - 1: https://goo.gl/maps/XsNx2XM21y41UQ7P7 |
      | field_c_p_view_link          | 0: [TEST] link 1 - 1: https://maps.google.com/maps?q=Australia |

    When I visit "civictheme_page" "[TEST] Page map test"
    And I wait 5 second
    And I should see an "div.civictheme-map" element
    And I should see an "div.civictheme-map.civictheme-theme-light" element
    And I should see an "div.civictheme-map.civictheme-map--vertical-space-both" element
    And I should not see an "div.civictheme-map.civictheme-theme-dark" element
    And I should not see an "div.civictheme-map--with-background" element
    And I should see an "div.civictheme-map__canvas" element
    And I should see an "iframe.civictheme-iframe.civictheme-theme-light" element
    And I should see an "div.civictheme-map__links" element
    And I should see an "div.civictheme-map__view_link" element
    And I should see an "div.civictheme-map__share_link" element
    And I should see the text "Australia"
    Then I should see the link "View in Google Maps" with "https://maps.google.com/maps?q=Australia" in 'div.civictheme-map__links'
    Then I should see the link "Share this Location" with "https://goo.gl/maps/XsNx2XM21y41UQ7P7" in 'div.civictheme-map__links'

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with map dark with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page map test" has "civictheme_map" paragraph:
      | field_c_p_address            | Australia                 |
      | field_c_p_theme              | dark                      |
      | field_c_p_background         | 1                         |
      | field_c_p_embed_url          | 0: [TEST] link 1 - 1: https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed |
      | field_c_p_share_link         | 0: [TEST] link 1 - 1: https://goo.gl/maps/XsNx2XM21y41UQ7P7 |
      | field_c_p_view_link          | 0: [TEST] link 1 - 1: https://maps.google.com/maps?q=Australia |

    When I visit "civictheme_page" "[TEST] Page map test"
    And I wait 5 second
    And I should see an "div.civictheme-map" element
    And I should see an "div.civictheme-map.civictheme-theme-dark" element
    And I should not see an "div.civictheme-map.civictheme-theme-light" element
    And I should see an "div.civictheme-map--with-background" element
    And I should see an "div.civictheme-map__canvas" element
    And I should see an "iframe.civictheme-iframe" element
    And I should see an "div.civictheme-map__links" element
    And I should see an "div.civictheme-map__view_link" element
    And I should see an "div.civictheme-map__share_link" element
    And I should see the text "Australia"
    Then I should see the link "View in Google Maps" with "https://maps.google.com/maps?q=Australia" in 'div.civictheme-map__links'
    Then I should see the link "Share this Location" with "https://goo.gl/maps/XsNx2XM21y41UQ7P7" in 'div.civictheme-map__links'
