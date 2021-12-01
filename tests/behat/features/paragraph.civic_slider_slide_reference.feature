@civic @paragraph @civic_slider_slide_reference
Feature: Tests the Slider slide - reference paragraph

  Ensure that Slider slide - reference paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider slide - reference" in the "civic_slider_slide_reference" row

  @api
  Scenario: Slider slide - reference paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_slider_slide_reference/fields"
    And I should see the text "field_c_p_reference" in the "Reference" row

  @api
  Scenario: Slider slide - reference paragraph field field_c_p_reference settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_slider_slide_reference/fields/paragraph.civic_slider_slide_reference.field_c_p_reference"
    And the "Label" field should contain "Reference"
    And the "Required field" checkbox should be checked
    Then the option "Default" from select "Reference method" is selected
    And the "Civic Event" checkbox should be checked
    And the "Civic Page" checkbox should be checked
    And the "Civic Project" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Civic Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civic-slider.dropbutton-action" element
    And I wait for AJAX to finish
    And I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And I see field "Title"
    And I click on "div.field--name-field-c-n-components div.field--name-field-c-p-slides .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_0_subform_field_c_p_slides_civic_slider_slide_reference_add_more" button
    And I wait for AJAX to finish
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_reference][0][target_id]']" element

  @api
  Scenario: Civic slider paragraph field_c_p_slides field settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_slider/fields/paragraph.civic_slider.field_c_p_slides"
    And the "Label" field should contain "Slides"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Slider slide - reference" checkbox should be checked
