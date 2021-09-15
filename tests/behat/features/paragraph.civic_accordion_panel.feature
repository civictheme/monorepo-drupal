@paragraph @civic_accordion_panel
Feature: Tests the Accordion panel

  Ensure that Accordion panel paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Accordion panel" in the "civic_accordion_panel" row

  @api
  Scenario: Accordion panel paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_accordion_panel/fields"
    And I should see the text "field_p_title" in the "Title" row
    And I should see the text "field_p_content" in the "Content" row
    And I should see the text "field_p_expand" in the "Expand" row
