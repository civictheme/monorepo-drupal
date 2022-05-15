@civictheme @civictheme_page @civictheme_attachment
Feature: View of Page content

  Ensure that Page content can be viewed correctly with attachment component.

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
      | title                             | status |
      | [TEST] Page attachment test       | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with attachment light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page attachment test" has "civictheme_attachment" paragraph:
      | field_c_p_title          | [TEST] attachment                  |
      | field_c_p_summary        | Summary text                       |
      | field_c_p_theme          | light                              |
      | field_c_p_background     | 0                                  |
      | field_c_p_image          | [TEST] CivicTheme Image            |

    When I visit "civictheme_page" "[TEST] Page attachment test"
    And I should see an "div.civictheme-attachment" element
    And I should not see an "div.civictheme-attachment.civictheme-content--with-background" element
    And I should see an "div.civictheme-attachment.civictheme-theme-light" element
    And I should see an "div.civictheme-attachment__content" element
    And I should see an "div.civictheme-attachment__text" element
    And I should see an "div.civictheme-attachment__title" element
    And I should see an "div.civictheme-attachment__summary" element
    And I should see an "ul.civictheme-attachment__links" element
    And I should see an "a.civictheme-link--attachment" element
    And I should see the text "[TEST] attachment"
    And I should see the text "Summary text"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with attachment dark with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page attachment test" has "civictheme_attachment" paragraph:
      | field_c_p_title          | [TEST] attachment                  |
      | field_c_p_summary        | Summary text                       |
      | field_c_p_theme          | light                              |
      | field_c_p_background     | 1                                  |
      | field_c_p_image          | [TEST] CivicTheme Image            |

    When I visit "civictheme_page" "[TEST] Page attachment test"
    And I should see an "div.civictheme-attachment" element
    And I should see an "div.civictheme-attachment.civictheme-content--with-background" element
    And I should see an "div.civictheme-attachment.civictheme-theme-dark" element
    And I should see an "div.civictheme-attachment__content" element
    And I should see an "div.civictheme-attachment__text" element
    And I should see an "div.civictheme-attachment__title" element
    And I should see an "div.civictheme-attachment__summary" element
    And I should see an "ul.civictheme-attachment__links" element
    And I should see an "a.civictheme-link--attachment" element
    And I should see the text "[TEST] attachment"
    And I should see the text "Summary text"
