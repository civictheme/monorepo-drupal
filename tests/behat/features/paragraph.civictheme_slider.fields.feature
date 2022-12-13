@p1 @civictheme @civictheme_slider
Feature: Slider, Slider Slide and Slider Slide Reference fields

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider" in the "civictheme_slider" row

  @api
  Scenario: Slider paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider/fields"
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_slides" in the "Slides" row
    And I should see the text "field_c_p_vertical_spacing" in the "Vertical spacing" row
    And I should see the text "field_c_p_background" in the "Background" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civictheme-slider.dropbutton-action" element
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-theme" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-vertical-spacing" element
    And the "field_c_n_components[0][subform][field_c_p_theme]" field should contain "light"
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-background-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-link-0-uri" element
    And I should see an "div.field--name-field-c-p-slides" element
    And I should see an "div.field--name-field-c-p-slides .form-required" element

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider reference slide" in the "civictheme_slider_slide" row

  @api
  Scenario: Slider Slide paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider_slide/fields"
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_image_position" in the "Image position" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_content" in the "Content" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_topic" in the "Topic" row
    And I should see the text "field_c_p_theme" in the "Theme" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civictheme-slider.dropbutton-action" element
    And I wait for AJAX to finish
    And I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And I see field "Title"
    And I press the "field_c_n_components_0_subform_field_c_p_slides_civictheme_slider_slide_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-title-0-value input.required" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-image-position" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-image-position select.required" element
    And I should see an "#field_c_p_image-media-library-wrapper-field_c_n_components-0-subform-field_c_p_slides-0-subform" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-content-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-slides-0-subform-field-c-p-link-0-uri" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_topic][0][target_id]']" element

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider reference slide" in the "civictheme_slider_slide_ref" row

  @api
  Scenario: Slider reference slide paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider_slide_ref/fields"
    And I should see the text "field_c_p_reference" in the "Reference" row

  @api
  Scenario: Slider reference slide paragraph field field_c_p_reference settings.
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
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_image_position]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_image_position]'].required" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_theme]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_link_text][0][value]']" element

  @api
  Scenario: CivicTheme slider paragraph field_c_p_slides field settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_slider/fields/paragraph.civictheme_slider.field_c_p_slides"
    And the "Label" field should contain "Slides"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Slider reference slide" checkbox should be checked
