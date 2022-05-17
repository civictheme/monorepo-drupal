@civictheme @paragraph @civictheme_event_card_ref
Feature: Tests the Event reference card paragraph

  Ensure that Event reference card paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Event reference card" in the "civictheme_event_card_ref" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_event_card_ref/fields"
    And I should see the text "field_c_p_reference" in the "Reference" row
    And I should see the text "field_c_p_theme" in the "Theme" row

  @api
  Scenario: Event reference card paragraph field_c_p_reference fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_event_card_ref/fields/paragraph.civictheme_event_card_ref.field_c_p_reference"
    And the "Label" field should contain "Reference"
    Then the option "Default" from select "Reference method" is selected
    Then the "Create referenced entities if they don't already exist" checkbox should not be checked
    And the "Event" checkbox should be checked

  @api
  Scenario: Card container paragraph field_c_p_cards fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_card_container/fields/paragraph.civictheme_card_container.field_c_p_cards"
    And the "Label" field should contain "Cards"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Event reference card" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civictheme_card_container_add_more" button
    And I wait for AJAX to finish
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-title-0-value" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-header-link-0-uri" element
    And I should see an "div.js-form-item-field-c-n-components-0-subform-field-c-p-column-count select.required" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_column_count]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_fill_width][value]']" element
    Then I click on "div.field--name-field-c-p-cards .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_0_subform_field_c_p_cards_civictheme_event_card_ref_add_more" button
    And I wait for AJAX to finish
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]'].required" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_reference][0][target_id]'].required" element
    And the option "Light" from select "Theme" is selected
