@p1 @civictheme @civictheme_next_step
Feature: Next step fields

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Next step" in the "civictheme_next_step" row

  @api
  Scenario: Next step paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_next_step/fields"
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_vertical_spacing" in the "Vertical spacing" row

  @api
  Scenario: Node type Page field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields/node.civictheme_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Next step" checkbox should be checked

  @api
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I press "Add Next step"
    Then I see field "field_c_n_components[0][subform][field_c_p_theme]"
    And the "field_c_n_components[0][subform][field_c_p_theme]" field should contain "light"
    And I see field "field_c_n_components[0][subform][field_c_p_vertical_spacing]"
    And I see field "Title"
    And I see field "Summary"
    And should not see an "textarea[name='field_c_n_components[0][subform][field_c_p_summary][0][value]'].required" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][uri]']" element
    And should not see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][uri]'].required" element
    And I should not see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][title]']" element
