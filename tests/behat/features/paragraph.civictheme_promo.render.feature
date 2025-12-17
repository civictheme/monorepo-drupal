@p0 @civictheme @civictheme_promo
Feature: Promo render

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page Promo test 1 | 1      |
      | [TEST] Page Promo test 2 | 1      |
      | [TEST] Cross Site Testing | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Promo light with vertical spacing without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo test 1" has "civictheme_promo" paragraph:
      | field_c_p_title            | [TEST] Promo title                                        |
      | field_c_p_theme            | light                                                     |
      | field_c_p_content:value    | [TEST] Content text                                       |
      | field_c_p_content:format   | civictheme_rich_text                                      |
      | field_c_p_vertical_spacing | both                                                      |
      | field_c_p_link             | 0: [TEST] link 1 PromoTest - 1: https://example.com/link1 |

    When I visit "civictheme_page" "[TEST] Page Promo test 1"
    And I should see an ".ct-promo" element
    And I should see an ".ct-promo.ct-theme-light" element
    And I should see an ".ct-promo.ct-vertical-spacing--both" element
    And I should not see an ".ct-promo--with-background" element
    And I should see the text "[TEST] Promo title"
    And I should see the text "[TEST] Content text"
    And I should see an ".ct-promo__title" element
    And I should see an ".ct-promo__content" element
    And the response should contain "[TEST] link 1"
    And the response should contain "PromoTest"
    And the response should contain "https://example.com/link1"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with promo dark without vertical space with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo test 2" has "civictheme_promo" paragraph:
      | field_c_p_title            | [TEST] Promo title                                        |
      | field_c_p_theme            | dark                                                      |
      | field_c_p_content:value    | [TEST] Content text                                       |
      | field_c_p_content:format   | civictheme_rich_text                                      |
      | field_c_p_vertical_spacing | 0                                                         |
      | field_c_p_background       | 1                                                         |
      | field_c_p_link             | 0: [TEST] link 2 PromoTest - 1: https://example.com/link2 |

    When I visit "civictheme_page" "[TEST] Page Promo test 2"
    And I should see an ".ct-promo" element
    And I should not see an ".ct-promo.ct-theme-light" element
    And I should see an ".ct-promo.ct-theme-dark" element
    And I should not see an ".ct-promo.ct-vertical-spacing--both" element
    And I should see an ".ct-promo--with-background" element
    And I should see the text "[TEST] Promo title"
    And I should see the text "[TEST] Content text"
    And I should see an ".ct-promo__title" element
    And I should see an ".ct-promo__content" element
    And the response should contain "[TEST] link 2"
    And the response should contain "PromoTest"
    And the response should contain "https://example.com/link2"

  @api @security
  Scenario:XSS - Promo
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Cross Site Testing" has "civictheme_promo" paragraph:
      | field_c_p_title            | <script id="test-promo--field_c_p_title">alert('[TEST] Promo field_c_p_title')</script>                                        |
      | field_c_p_theme            | dark                                                      |
      | field_c_p_content:value    | <script id="test-promo--field_c_p_content">alert('[TEST] Promo field_c_p_content')</script>                                       |
      | field_c_p_content:format   | civictheme_rich_text                                      |
      | field_c_p_vertical_spacing | 0                                                         |
      | field_c_p_background       | 1                                                         |
      | field_c_p_link             | 0: <script id="test-promo--field_c_p_link">alert('field_c_p_link');</script> - 1: https://example.com/link2 |

    When I visit "civictheme_page" "[TEST] Cross Site Testing"
    And I should see an ".ct-promo" element
    And I should see an ".ct-promo__title" element
    And I should not see an "script#test-promo--field_c_p_title" element
    And I should see the text "alert('[TEST] Promo field_c_p_title')"
    And I should see an ".ct-promo__content" element
    And I should not see an "script#test-promo--field_c_p_content" element
    And I should see the text "alert('[TEST] Promo field_c_p_content')"
    And I should not see an "script#test-promo--field_c_p_link" element
    And I should see the text "alert('field_c_p_link')"
