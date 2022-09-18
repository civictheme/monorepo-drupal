@civictheme @paragraph @civictheme_map
Feature: Tests the Map paragraph

  Ensure that Map paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Map" in the "civictheme_map" row

  @api
  Scenario: Map paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_map/fields"
    And I should see the text "field_c_p_address" in the "Address" row
    And I should see the text "field_c_p_view_link" in the "View link" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_space" in the "With space" row
    And I should see the text "field_c_p_embed_url" in the "Embed URL" row
    And I should see the text "field_c_p_background" in the "Background" row

  @api
  Scenario: Page content type field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields/node.civictheme_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Map" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I press the "field_c_n_components_civictheme_map_add_more" button
    And I wait for AJAX to finish
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_address][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_address][0][value]'].required" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_space]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_embed_url][0][uri]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_embed_url][0][uri]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_view_link][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_background][value]']" element
    And the option "Light" from select "Theme" is selected
