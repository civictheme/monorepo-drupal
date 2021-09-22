@paragraph @civic_promo
Feature: Tests the Promo paragraph

  Ensure that Promo paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Promo" in the "civic_promo" row

  @api
  Scenario: Promo paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_promo/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_theme" in the "Theme" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on "div.field--name-field-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_civic_promo_add_more" button
    And I wait for AJAX to finish
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_summary][0][value]']" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_summary][0][value]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][uri]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][uri]'].required" element
