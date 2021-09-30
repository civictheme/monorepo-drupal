@paragraph @civic_accordion
Feature: Tests the Accordion

  Ensure that Accordion paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Accordion" in the "civic_accordion" row

  @api
  Scenario: Accordion panel paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_accordion/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_panels" in the "Panels" row
    And I should see the text "field_c_p_expand" in the "Expand all" row
    And I should see the text "field_c_p_default_panel" in the "Default Panel" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_civic_accordion_add_more" button
    And I wait for AJAX to finish
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_expand][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_panels][0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_panels][0][subform][field_c_p_title][0][value]'].required" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_panels][0][subform][field_c_p_content][0][value]']" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_panels][0][subform][field_c_p_content][0][value]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_panels][0][subform][field_c_p_expand][value]']" element
