@civic @paragraph @civic_card_navigation
Feature: Tests the Card container paragraph

  Ensure that Card container paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Navigation card" in the "civic_card_navigation" row

  @api
  Scenario: Card container paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_navigation/fields"
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_summary" in the "Summary" row

  @api
  Scenario: Card container paragraph field_c_p_cards fields settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields/paragraph.civic_card_container.field_c_p_cards"
    And the "Label" field should contain "Cards"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Navigation card" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civic_card_container_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-link-0-uri" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-column-count select.required" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_column_count]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_fill_width][value]']" element
    And I click on "div.field--name-field-c-p-cards .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_0_subform_field_c_p_cards_civic_card_navigation_add_more" button
    And I wait for AJAX to finish
    Then select "field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]" should have an option "light"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]'].required" element
    And I should see an "textarea[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][title]']" element
