@civic @paragraph @civic_slider
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
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_slides" in the "Slides" row

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
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-theme" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-theme select.required" element
    And the option "Light" from select "Theme" is selected
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-link-0-uri" element
    And I should see an "div.field--name-field-c-p-slides" element
    And I should see an "div.field--name-field-c-p-slides .form-required" element

