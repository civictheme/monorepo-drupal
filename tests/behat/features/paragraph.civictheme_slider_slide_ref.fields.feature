@civictheme @paragraph @civictheme_slider_slide_ref
Feature: Tests the Slider slide - reference paragraph

  Ensure that Slider slide - reference paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider slide - reference" in the "civictheme_slider_slide_ref" row

  @api
  Scenario: Slider slide - reference paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider_slide_ref/fields"
    And I should see the text "field_c_p_reference" in the "Reference" row

  @api
  Scenario: Slider slide - reference paragraph field field_c_p_reference settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider_slide_ref/fields/paragraph.civictheme_slider_slide_ref.field_c_p_reference"
    And the "Label" field should contain "Reference"
    And the "Required field" checkbox should be checked
    Then the option "Default" from select "Reference method" is selected
    And the "Event" checkbox should be checked
    And the "Page" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civictheme-slider.dropbutton-action" element
    And I wait for AJAX to finish
    And I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And I see field "Title"
    And I click on "div.field--name-field-c-n-components div.field--name-field-c-p-slides .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_0_subform_field_c_p_slides_civictheme_slider_slide_ref_add_more" button
    And I wait for AJAX to finish
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_reference][0][target_id]']" element

  @api
  Scenario: CivicTheme slider paragraph field_c_p_slides field settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider/fields/paragraph.civictheme_slider.field_c_p_slides"
    And the "Label" field should contain "Slides"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Slider slide - reference" checkbox should be checked
