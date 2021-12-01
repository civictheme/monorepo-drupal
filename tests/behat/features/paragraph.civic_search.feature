@civic @paragraph @civic_search
Feature: Tests the Civic search paragraph

  Ensure that Civic search paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Search" in the "civic_search" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_search/fields"
    And I should see the text "field_c_p_button_text" in the "Button text" row
    And I should see the text "field_c_p_help_text" in the "Help text" row
    And I should see the text "field_c_p_placeholder" in the "Placeholder text" row
    And I should see the text "field_c_p_search_url" in the "Search url" row
    And I should see the text "field_c_p_title" in the "Title" row

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-1 a" element
    And I click on "div.field--name-field-c-n-banner-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_banner_components_civic_search_add_more" button
    And I wait for AJAX to finish
    And I should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_title][0][value]']" element
    And I should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_placeholder][0][value]']" element
    And I should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_help_text][0][value]']" element
    And I should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_button_text][0][value]']" element
    And I should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_search_url][0][value]']" element

  @api
  Scenario: Civic page node field_c_n_banner_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civic_page/fields/node.civic_page.field_c_n_banner_components"
    And the "Label" field should contain "Banner components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Search" checkbox should be checked
