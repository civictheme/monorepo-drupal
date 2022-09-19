@civictheme @paragraph @civictheme_quote
Feature: Tests the Quote paragraph

  Ensure that Quote paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Quote" in the "civictheme_quote" row

  @api
  Scenario: Map paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_quote/fields"
    And I should see the text "field_c_p_author" in the "Author" row
    And I should see the text "field_c_p_content" in the "Content" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_vertical_spacing" in the "Vertical space" row

  @api
  Scenario: Page content type field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields/node.civictheme_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Quote" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civictheme_quote_add_more" button
    And I wait for AJAX to finish
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_content][0][value]']" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_content][0][value]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_author][0][value]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And the option "Light" from select "Theme" is selected
