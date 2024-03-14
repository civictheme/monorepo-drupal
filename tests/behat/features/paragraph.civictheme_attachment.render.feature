@p1 @civictheme @civictheme_attachment
Feature: Attachment render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |
      | test_pdf.pdf   | public://civictheme_test/test_pdf.pdf   | test_pdf.pdf   |

    And "civictheme_document" media:
      | name                  | field_c_m_document |
      | [TEST] CivicTheme PDF | test_pdf.pdf       |

    And "civictheme_page" content:
      | title                       | status |
      | [TEST] Page attachment test | 1      |

  @api
  Scenario: Attachment light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page attachment test" has "civictheme_attachment" paragraph:
      | field_c_p_title          | [TEST] attachment     |
      | field_c_p_content:value  | Content text          |
      | field_c_p_content:format | civictheme_rich_text  |
      | field_c_p_theme          | light                 |
      | field_c_p_background     | 0                     |
      | field_c_p_attachments    | [TEST] CivicTheme PDF |

    When I visit "civictheme_page" "[TEST] Page attachment test"
    And I should see an ".ct-attachment" element
    And I should not see an ".ct-attachment.ct-attachment--with-background" element
    And I should see an ".ct-attachment.ct-theme-light" element
    And I should see an ".ct-attachment__title" element
    And I should see an ".ct-attachment__content" element
    And I should see the text "[TEST] attachment"
    And I should see the text "Content text"
    And I should see the text "(PDF"
    And I should see the text "[TEST] CivicTheme PDF"
    And I should not see the text "test_pdf.pdf"

  @api
  Scenario: Attachment dark with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page attachment test" has "civictheme_attachment" paragraph:
      | field_c_p_title          | [TEST] attachment     |
      | field_c_p_content:value  | Content text          |
      | field_c_p_content:format | civictheme_rich_text  |
      | field_c_p_theme          | dark                  |
      | field_c_p_background     | 1                     |
      | field_c_p_attachments    | [TEST] CivicTheme PDF |

    When I visit "civictheme_page" "[TEST] Page attachment test"
    And I should see an ".ct-attachment" element
    And I should see an ".ct-attachment.ct-attachment--with-background" element
    And I should see an ".ct-attachment.ct-theme-dark" element
    And I should see an ".ct-attachment__title" element
    And I should see an ".ct-attachment__content" element
    And I should see the text "[TEST] attachment"
    And I should see the text "Content text"
    And I should see the text "(PDF"
    And I should see the text "[TEST] CivicTheme PDF"
    And I should not see the text "test_pdf.pdf"

  @api
  Scenario: Attachment dark with background & Disabled Use name of media
    Given I am logged in as a user with the "Site Administrator" role
    When I visit current theme settings page
    And I uncheck the box "Use name of media"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page attachment test" has "civictheme_attachment" paragraph:
      | field_c_p_title          | [TEST] attachment     |
      | field_c_p_content:value  | Content text          |
      | field_c_p_content:format | civictheme_rich_text  |
      | field_c_p_theme          | dark                  |
      | field_c_p_background     | 1                     |
      | field_c_p_attachments    | [TEST] CivicTheme PDF |

    When I visit "civictheme_page" "[TEST] Page attachment test"
    And I should see an ".ct-attachment" element
    And I should see an ".ct-attachment.ct-attachment--with-background" element
    And I should see an ".ct-attachment.ct-theme-dark" element
    And I should see an ".ct-attachment__title" element
    And I should see an ".ct-attachment__content" element
    And I should see the text "[TEST] attachment"
    And I should see the text "Content text"
    And I should see the text "(PDF"
    And I should not see the text "[TEST] CivicTheme PDF"
    And I should see the text "test_pdf.pdf"
