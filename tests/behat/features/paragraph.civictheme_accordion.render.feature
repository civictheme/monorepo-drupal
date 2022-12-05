@p1 @civictheme @civictheme_accordion
Feature: Accordion render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |
      | test_pdf.pdf   | public://civictheme_test/test_pdf.pdf   | test_pdf.pdf   |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                      | status |
      | [TEST] Page accordion test | 1      |

  @api
  Scenario: Accordion light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page accordion test" has "civictheme_accordion" paragraph:
      | field_c_p_theme      | light |
      | field_c_p_background | 0     |
      | field_c_p_expand     | 0     |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page accordion test" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 1                                                                                                                            |
      | field_c_p_expand         | 0                                                                                                                                                   |
      | field_c_p_content:value  | <h2>[TEST] content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page accordion test" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 2                                                                                                                            |
      | field_c_p_expand         | 0                                                                                                                                                   |
      | field_c_p_content:value  | <h2>[TEST] content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                |

    When I visit "civictheme_page" "[TEST] Page accordion test"
    And I should see an "div.ct-accordion" element
    And I should not see an "div.ct-accordion.ct-accordion--with-background" element
    And I should see an "div.ct-accordion.ct-theme-light" element
    And I should see an "div[data-collapsible-panel]" element
    And I should see an "div.ct-accordion__inner" element
    And I should see an "ul.ct-accordion__list" element
    And I should see the text "[TEST] Accordion panel 1"
    And I should see the text "[TEST] Accordion panel 2"
    And I should not see an "[data-collapsible-trigger][aria-expanded='true']" element

  @api @javascript
  Scenario: Accordion dark with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page accordion test" has "civictheme_accordion" paragraph:
      | field_c_p_theme      | dark |
      | field_c_p_background | 1    |
      | field_c_p_expand     | 1    |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page accordion test" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 1                                                                                                                            |
      | field_c_p_expand         | 0                                                                                                                                                   |
      | field_c_p_content:value  | <h2>[TEST] content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page accordion test" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 2                                                                                                                            |
      | field_c_p_expand         | 0                                                                                                                                                   |
      | field_c_p_content:value  | <h2>[TEST] content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                |

    When I visit "civictheme_page" "[TEST] Page accordion test"
    And I should see an "div.ct-accordion" element
    And I should see an "div.ct-accordion.ct-accordion--with-background" element
    And I should not see an "div.ct-accordion.ct-theme-light" element
    And I should see an "div.ct-accordion.ct-theme-dark" element
    And I should see an "div[data-collapsible-panel]" element
    And I should see an "div.ct-accordion__inner" element
    And I should see an "ul.ct-accordion__list" element
    And I should see the text "[TEST] Accordion panel 1"
    And I should see the text "[TEST] Accordion panel 2"
    And I wait 2 second
    And I should see an "[data-collapsible-trigger][aria-expanded='true']" element
    And I should see an "[data-collapsible-panel][aria-hidden='false']" element
