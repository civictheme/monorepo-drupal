@paragraph @civic_quick_links
Feature: Tests the Quick links paragraph type and fields.

  Ensure that Quick links paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Quick links" in the "civic_quick_links" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_quick_links/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_links" in the "Links" row

  @api
  Scenario: Block type field_c_b_components fields settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/block/block-content/manage/civic_component_block/fields/block_content.civic_component_block.field_c_b_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Quick links" checkbox should be checked

  @api @javascript
  Scenario: Component Block paragraph reference exists and works
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "block/add/civic_component_block"
    And I click on "div.field--name-field-b-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_b_components_civic_quick_links_add_more" button
    And I wait for AJAX to finish
    And should see an "select[name='field_c_b_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_b_components[0][subform][field_c_p_theme]'].required" element
    And should see an "input[name='field_c_b_components[0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_b_components[0][subform][field_c_p_links][0][uri]']" element
    And should see an "input[name='field_c_b_components[0][subform][field_c_p_links][0][uri]'].required" element
