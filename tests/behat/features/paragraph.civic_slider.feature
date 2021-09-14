@paragraph @civic_slider
Feature: Tests the Slider paragraph

  Ensure that the Slider paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Slider" in the "civic_slider" row

  @api
  Scenario: Slider paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_slider/fields"
    And I should see the text "field_p_theme" in the "Theme" row
    And I should see the text "field_p_title" in the "Title" row
    And I should see the text "field_p_link" in the "Link" row
    And I should see the text "field_p_slides" in the "Slides" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on "div.field--name-field-n-components .paragraphs-add-wrapper .dropbutton-toggle button" button
    And I wait for AJAX to finish
    And I press the "field_n_components_civic_slider_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-theme" element
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-theme select.required" element
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-title-0-value" element
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-link-0-uri" element
    And I should see an "div.field--name-field-p-slides" element
    And I should see an "div.field--name-field-p-slides .form-required" element

