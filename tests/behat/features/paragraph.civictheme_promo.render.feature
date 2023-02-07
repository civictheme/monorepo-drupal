@p0 @civictheme @civictheme_promo
Feature: Promo render

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page Promo test 1 | 1      |
      | [TEST] Page Promo test 2 | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Promo light with vertical spacing
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo test 1" has "civictheme_promo" paragraph:
      | field_c_p_title            | [TEST] Promo title                              |
      | field_c_p_theme            | light                                           |
      | field_c_p_content          | [TEST] Content text                             |
      | field_c_p_vertical_spacing | both                                            |
      | field_c_p_link             | 0: [TEST] link 1 - 1: https://example.com/link1 |

    When I visit "civictheme_page" "[TEST] Page Promo test 1"
    And I should see an ".ct-promo" element
    And I should see an ".ct-promo.ct-theme-light" element
    And I should see an ".ct-promo.ct-vertical-spacing--both" element
    And I should see the text "[TEST] Promo title"
    And I should see the text "[TEST] Content text"
    And I should see an ".ct-promo__title" element
    And I should see an ".ct-promo__content" element
    And the response should contain "[TEST] link 1"
    And the response should contain "https://example.com/link1"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with promo dark without vertical space
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo test 2" has "civictheme_promo" paragraph:
      | field_c_p_title            | [TEST] Promo title                              |
      | field_c_p_theme            | dark                                            |
      | field_c_p_content          | [TEST] Content text                             |
      | field_c_p_vertical_spacing | 0                                               |
      | field_c_p_link             | 0: [TEST] link 2 - 1: https://example.com/link2 |

    When I visit "civictheme_page" "[TEST] Page Promo test 2"
    And I should see an ".ct-promo" element
    And I should not see an ".ct-promo.ct-theme-light" element
    And I should see an ".ct-promo.ct-theme-dark" element
    And I should not see an ".ct-promo.ct-vertical-spacing--both" element
    And I should see the text "[TEST] Promo title"
    And I should see the text "[TEST] Content text"
    And I should see an ".ct-promo__title" element
    And I should see an ".ct-promo__content" element
    And the response should contain "[TEST] link 2"
    And the response should contain "https://example.com/link2"
