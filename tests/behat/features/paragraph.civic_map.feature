@paragraph @civic_map
Feature: Tests the Map paragraph

  Ensure that Map paragraphs exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Map" in the "civic_map" row

  @api
  Scenario: Map paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_map/fields"
    And I should see the text "field_p_address" in the "Address" row
    And I should see the text "field_p_share_link" in the "Share link" row
    And I should see the text "field_p_view_link" in the "View link" row
    And I should see the text "field_p_zoom" in the "Zoom" row
    And I should see the text "field_p_theme" in the "Theme" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on "div.field--name-field-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_n_components_civic_map_add_more" button
    And I wait for AJAX to finish
    And should see an "input[name='field_n_components[0][subform][field_p_address][0][value]']" element
    And should see an "input[name='field_n_components[0][subform][field_p_address][0][value]'].required" element
    And should see an "select[name='field_n_components[0][subform][field_p_theme]']" element
    And should see an "select[name='field_n_components[0][subform][field_p_theme]'].required" element
    And should see an "input[name='field_n_components[0][subform][field_p_zoom][0][value]']" element
    And should see an "input[name='field_n_components[0][subform][field_p_zoom][0][value]'].required" element
    And should see an "input[name='field_n_components[0][subform][field_p_view_link][0][uri]']" element
    And should see an "input[name='field_n_components[0][subform][field_p_share_link][0][uri]']" element

  @api @javascript
  Scenario: Component Block paragraph reference exists and works
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "block/add/civic_component_block"
    And I click on "div.field--name-field-b-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_b_components_civic_map_add_more" button
    And I wait for AJAX to finish
    And should see an "input[name='field_b_components[0][subform][field_p_address][0][value]']" element
    And should see an "input[name='field_b_components[0][subform][field_p_address][0][value]'].required" element
    And should see an "select[name='field_b_components[0][subform][field_p_theme]']" element
    And should see an "select[name='field_b_components[0][subform][field_p_theme]'].required" element
    And should see an "input[name='field_b_components[0][subform][field_p_zoom][0][value]']" element
    And should see an "input[name='field_b_components[0][subform][field_p_zoom][0][value]'].required" element
    And should see an "input[name='field_b_components[0][subform][field_p_view_link][0][uri]']" element
    And should see an "input[name='field_b_components[0][subform][field_p_share_link][0][uri]']" element
