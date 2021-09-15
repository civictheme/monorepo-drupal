@paragraph @civic_card_container
Feature: Tests the Card container paragraph

  Ensure that Card container paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Card container" in the "civic_card_container" row

  @api
  Scenario: Card container paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields"
    And I should see the text "field_p_cards" in the "Cards" row
    And I should see the text "field_p_column_count" in the "Column count" row
    And I should see the text "field_p_fill_width" in the "Fill width" row
    And I should see the text "field_p_link" in the "Link" row
    And I should see the text "field_p_title" in the "Title" row

  @api
  Scenario: Card container paragraph field_p_cards fields settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields/paragraph.civic_card_container.field_p_cards"
    And the "Label" field should contain "Cards"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Card task" checkbox should be checked
    And the "Card container" checkbox should not be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on "div.field--name-field-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_n_components_civic_card_container_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-title-0-value" element
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-link-0-uri" element
    And I should see an "div.js-form-item-field-n-components-0-subform-field-p-column-count select.required" element
    And I should see an "select[name='field_n_components[0][subform][field_p_column_count]']" element
    And I should see an "input[name='field_n_components[0][subform][field_p_fill_width][value]']" element
    And I click on "div.field--name-field-p-cards .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I should see an "input[name='field_n_components_0_subform_field_p_cards_civic_card_task_add_more']" element
