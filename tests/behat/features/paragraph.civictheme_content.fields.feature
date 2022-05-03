@civictheme @paragraph @civictheme_content
Feature: Tests the Content paragraph

  Ensure that Content paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Content" in the "civictheme_content" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_content/fields"
    And I should see the text "field_c_p_content" in the "Content" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_background" in the "Background" row
    And I should see the text "field_c_p_space" in the "With space" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"

    When I click on ".field-group-tabs-wrapper .horizontal-tab-button-1 a" element
    And I press the "field_c_n_banner_components_civictheme_content_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-banner-components-0-subform-field-c-p-content-0-value" element
    And I should see an "div.js-form-item-field-c-n-banner-components-0-subform-field-c-p-theme" element
    And I should see an "div.js-form-item-field-c-n-banner-components-0-subform-field-c-p-background-value" element
    And I should see an "div.js-form-item-field-c-n-banner-components-0-subform-field-c-p-space" element
    And I should see an ".js-form-item-field-c-n-banner-components-0-subform-field-c-p-theme .required" element
    And the option "Light" from select "Theme" is selected

    And I press the "field_c_n_banner_components_bott_civictheme_content_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-banner-components-bott-0-subform-field-c-p-content-0-value" element
    And I should see an "div.js-form-item-field-c-n-banner-components-bott-0-subform-field-c-p-theme" element
    And I should see an "div.js-form-item-field-c-n-banner-components-0-subform-field-c-p-background-value" element
    And I should see an ".js-form-item-field-c-n-banner-components-bott-0-subform-field-c-p-theme .required" element
