@p0 @civictheme @civictheme_card @civictheme_publication_card
Feature: Publication card render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |
      | test_pdf1.pdf  | public://civictheme_test/test_pdf1.pdf  | test_pdf.pdf   |
      | test_pdf2.pdf  | public://civictheme_test/test_pdf2.pdf  | test_pdf.pdf   |
      | test_pdf3.pdf  | public://civictheme_test/test_pdf3.pdf  | test_pdf.pdf   |
      | test_pdf4.pdf  | public://civictheme_test/test_pdf4.pdf  | test_pdf.pdf   |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                              | status | field_c_n_site_section |
      | [TEST] Page Publication cards test | 1      |                        |

    And "civictheme_document" media:
      | name              | field_c_m_document |
      | [TEST] Document 1 | test_pdf1.pdf      |
      | [TEST] Document 2 | test_pdf2.pdf      |
      | [TEST] Document 3 | test_pdf3.pdf      |
      | [TEST] Document 4 | test_pdf4.pdf      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Publication cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Publication cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Publication card manual list                    |
      | field_c_p_column_count    | 4                                                      |
      | field_c_p_list_link_above | 0: View all publication cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                      |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Publication card manual list" has "civictheme_publication_card" paragraph:
      | field_c_p_title    | Publication card title 1 |
      | field_c_p_summary  | Summary text 1           |
      | field_c_p_image    | [TEST] CivicTheme Image  |
      | field_c_p_document | [TEST] Document 1        |
      | field_c_p_theme    | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Publication card manual list" has "civictheme_publication_card" paragraph:
      | field_c_p_title    | Publication card title 2 |
      | field_c_p_summary  | Summary text 2           |
      | field_c_p_image    | [TEST] CivicTheme Image  |
      | field_c_p_document | [TEST] Document 2        |
      | field_c_p_theme    | light                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Publication card manual list" has "civictheme_publication_card" paragraph:
      | field_c_p_title    | Publication card title 3 |
      | field_c_p_summary  | Summary text 3           |
      | field_c_p_image    | [TEST] CivicTheme Image  |
      | field_c_p_document | [TEST] Document 3        |
      | field_c_p_theme    | dark                     |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Publication card manual list" has "civictheme_publication_card" paragraph:
      | field_c_p_title    | Publication card title 4 |
      | field_c_p_summary  | Summary text 4           |
      | field_c_p_image    | [TEST] CivicTheme Image  |
      | field_c_p_document | [TEST] Document 4        |
      | field_c_p_theme    | dark                     |

    When I visit "civictheme_page" "[TEST] Page Publication cards test"
    Then I should see the text "[TEST] Publication card manual list"
    And I should see the link "View all publication cards" with "https://example.com" in '.ct-list'
    And I should see 1 ".ct-list" elements
    And I should see 4 ".ct-publication-card" elements
    And I should see 2 ".ct-publication-card.ct-theme-light" elements
    And I should see 2 ".ct-publication-card.ct-theme-dark" elements
    And I should see 4 ".ct-publication-card__content" elements
    And I should see 4 ".ct-publication-card__title" elements
    And I should see 4 ".ct-publication-card__summary" elements
    And I should see the text "Publication card title 1"
    And I should see the text "Publication card title 2"
    And I should see the text "Publication card title 3"
    And I should see the text "Publication card title 4"
    And I should see the text "test_pdf1.pdf"
    And I should see the text "test_pdf2.pdf"
    And I should see the text "test_pdf3.pdf"
    And I should see the text "test_pdf4.pdf"
