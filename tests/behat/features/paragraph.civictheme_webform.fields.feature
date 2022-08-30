@civictheme @paragraph @civictheme_webform
Feature: Tests the webform paragraph

  Ensure that Webform paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Webform" in the "civictheme_webform" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_webform/fields"
    And I should see the text "field_c_p_webform" in the "Webform" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civictheme-webform.dropbutton-action" element
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-webform-0-target-id" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-webform-0-target-id select.required" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-theme" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-theme select.required" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-space" element
    And I should not see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-space select.required" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-background-value" element
    And I should not see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-background-value input.required" element
