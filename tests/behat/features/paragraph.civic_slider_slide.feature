@civic @paragraph @civic_slider_slide
Feature: Tests the Slider Slide paragraph

  Ensure that the Slider Slide paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider slide" in the "civic_slider_slide" row

  @api
  Scenario: Slider Slide paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_slider_slide/fields"
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_image_position" in the "Image position" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_content" in the "Content" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_topic" in the "Topic" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civic-slider.dropbutton-action" element
    And I wait for AJAX to finish
    And I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And I see field "Title"
    And I press the "field_c_n_components_0_subform_field_c_p_slides_civic_slider_slide_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-title-0-value input.required" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-image-position" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-image-position select.required" element
    And I should see an "#field_c_p_image-media-library-wrapper-field_c_n_components-0-subform-field_c_p_slides-0-subform" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-content-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-link-0-uri" element
    And I should see an "div.form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-topic-0-target-id" element
