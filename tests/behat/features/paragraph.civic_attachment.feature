@civic @paragraph @civic_attachment
Feature: Tests the Attachment

  Ensure that Attachment paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Attachment" in the "civic_attachment" row

  @api
  Scenario: Attachment paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_attachment/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_attachments" in the "Attachments" row
    And I should see the text "field_c_p_icon" in the "Icon" row
    And I should see the text "field_c_p_image" in the "Image" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Civic Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_civic_attachment_add_more" button
    And I wait for AJAX to finish
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_summary][0][value]']" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_summary][0][value]'].required" element
    And should see an "#field_c_p_image-media-library-wrapper-field_c_n_components-0-subform" element
    And should see an "#field_c_p_attachments-media-library-wrapper-field_c_n_components-0-subform" element
    And should see an "#field_c_p_attachments-media-library-wrapper-field_c_n_components-0-subform.required" element
    And should see an "#field_c_p_icon-media-library-wrapper-field_c_n_components-0-subform" element
    And the option "Light" from select "Theme" is selected

  @api @javascript
  Scenario: Civic Component paragraph reference exists on Component block
    Given I am logged in as a user with the "Civic Site Administrator" role
    When I go to "block/add/civic_component_block"
    And I click on "div.field--name-field-c-b-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_b_components_civic_attachment_add_more" button
    And I wait for AJAX to finish
    And should see an "select[name='field_c_b_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_b_components[0][subform][field_c_p_theme]'].required" element
    And should see an "input[name='field_c_b_components[0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_b_components[0][subform][field_c_p_title][0][value]'].required" element
    And should see an "textarea[name='field_c_b_components[0][subform][field_c_p_summary][0][value]']" element
    And should see an "textarea[name='field_c_b_components[0][subform][field_c_p_summary][0][value]'].required" element
    And should see an "#field_c_p_image-media-library-wrapper-field_c_b_components-0-subform" element
    And should see an "#field_c_p_attachments-media-library-wrapper-field_c_b_components-0-subform" element
    And should see an "#field_c_p_attachments-media-library-wrapper-field_c_b_components-0-subform.required" element
    And should see an "#field_c_p_icon-media-library-wrapper-field_c_b_components-0-subform" element

