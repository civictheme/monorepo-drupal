@civictheme @paragraph @civictheme_iframe
Feature: Tests the IFrame paragraph

  Ensure that IFrame paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "iFrame" in the "civictheme_iframe" row

  @api
  Scenario: IFrame paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_iframe/fields"
    And I should see the text "field_c_p_url" in the "URL" row
    And I should see the text "field_c_p_width" in the "Width" row
    And I should see the text "field_c_p_height" in the "Height" row
    And I should see the text "field_c_p_attributes" in the "Attributes" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_space" in the "With space" row

  @api
  Scenario: Page content type field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields/node.civictheme_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "iFrame" checkbox should be checked
