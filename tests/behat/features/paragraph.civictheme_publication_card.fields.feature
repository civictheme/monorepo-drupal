@civictheme @paragraph @civictheme_publication_card
Feature: Tests the CivicTheme publication card

  Ensure that CivicTheme publication card paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Publication card" in the "civictheme_publication_card" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_publication_card/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_size" in the "Size" row
    And I should see the text "field_c_p_document" in the "Document" row

