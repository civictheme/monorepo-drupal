@civictheme @paragraph @civictheme_campaign
Feature: Tests the Campaign paragraph

  Ensure that the Campaign paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Campaign" in the "civictheme_campaign" row

  @api
  Scenario: Campaign paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_campaign/fields"
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_image_position" in the "Image position" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_content" in the "Content" row
    And I should see the text "field_c_p_date" in the "Date" row
    And I should see the text "field_c_p_topic" in the "Topic" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_vertical_spacing" in the "Vertical spacing" row

  @api
  Scenario: Page content type field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields/node.civictheme_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Campaign" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I click on "div.field--name-field-c-n-components .add-more-button-civictheme-campaign.dropbutton-action" element
    And I wait for AJAX to finish
    And I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_content][0][value]']" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_summary][0][value]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-image-position" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-image-position select.required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_date][0][value][date]']" element
    And I should see an "#field_c_p_image-media-library-wrapper-field_c_n_components-0-subform" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_topic][0][target_id]']" element
    And the option "Light" from select "Theme" is selected
