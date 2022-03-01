@civic @paragraph @civic_next_step
Feature: Test the Next step paragraph

  Ensure that Next step paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Next step" in the "civic_next_step" row

  @api
  Scenario: Next step paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_next_step/fields"
    And I should see the text "field_c_p_icon" in the "Icon" row
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_space" in the "With space" row

  @api
  Scenario: Node type Page field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civic_page/fields/node.civic_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Next step" checkbox should be checked

  @api
  Scenario: Block type Civic Component field_c_b_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civic_component_block/fields/block_content.civic_component_block.field_c_b_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Next step" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civic_next_step_add_more" button
    And I wait for AJAX to finish
    And I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And the option "Light" from select "Theme" is selected
    And I see field "field_c_n_components[0][subform][field_c_p_space]"
    And I see field "Title"
    And I see field "Summary"
    And should see "The summary field may contain up to 100 characters. Any characters past the 100 character limit will not show for users." in the ".form-item-field-c-n-components-0-subform-field-c-p-summary-0-value" element
    And I should see an "input[name='field_c_p_image-media-library-open-button-field_c_n_components-0-subform']" element
    And I should see an "input[name='field_c_p_icon-media-library-open-button-field_c_n_components-0-subform']" element
