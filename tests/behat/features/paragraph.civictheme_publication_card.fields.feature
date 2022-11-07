@civictheme @paragraph @civictheme_publication_card
Feature: Tests the CivicTheme publication card

  Ensure that CivicTheme publication card paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Publication card" in the "civictheme_publication_card" row

  @api
  Scenario: Publication card paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_publication_card/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_size" in the "Size" row
    And I should see the text "field_c_p_document" in the "Document" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' and 'field Components' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_civictheme_manual_list_add_more" button
    And I wait for AJAX to finish
    And I click on "div.field--name-field-c-n-components .field--name-field-c-p-list-items .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_0_subform_field_c_p_list_items_civictheme_publication_card_add_more" button
    And I wait for AJAX to finish
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_theme]'].required" element
    And the option "Light" from select "Theme" is selected
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_title][0][value]'].required" element


