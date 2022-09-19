@civictheme @paragraph @civictheme_manual_list
Feature: Tests the Listing paragraph

  Ensure that Listing paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Manual List" in the "civictheme_manual_list" row

  @api
  Scenario: Listing paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_manual_list/fields"
    And I should see the text "field_c_p_background" in the "Background" row
    And I should see the text "field_c_p_column_count" in the "Column count" row
    And I should see the text "field_c_p_content" in the "Content" row
    And I should see the text "field_c_p_list_item_view_as" in the "Display items as" row
    And I should see the text "field_c_p_fill_width" in the "Fill width in the last row" row
    And I should see the text "field_c_p_list_item_theme" in the "Item theme" row
    And I should see the text "field_c_p_list_link_above" in the "Link above" row
    And I should see the text "field_c_p_list_link_below" in the "Link below" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_space" in the "With space" row
    And I should see the text "field_c_p_list_items" in the "List items" row

  @api
  Scenario: Manual List paragraph field_c_p_list_items fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_manual_list/fields/paragraph.civictheme_manual_list.field_c_p_list_items"
    And the "Label" field should contain "List items"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Event card" checkbox should be checked
    And the "Event reference card" checkbox should be checked
    And the "Navigation card" checkbox should be checked
    And the "Navigation reference card" checkbox should be checked
    And the "Promo card" checkbox should be checked
    And the "Promo reference card" checkbox should be checked
    And the "Publication card" checkbox should be checked
    And the "Service card" checkbox should be checked
    And the "Subject card" checkbox should be checked
    And the "Subject reference card" checkbox should be checked
    And the "Task card" checkbox should be checked
    And the "Manual List" checkbox should not be checked

  @api @javascript
  Scenario: Manual List paragraph fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"

    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civictheme_manual_list_add_more" button
    And I wait for AJAX to finish
    And I see the text "Content"
    And I see the text "List items"
    And should see a "input[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_event_card_add_more']" element
    And should see a "input[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_event_card_ref_add_more']" element
    And should see a "input[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_navigation_card_add_more']" element
    And should see a "input[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_navigation_card_ref_add_more']" element

    And I scroll to an element with id "edit-field-c-n-components-wrapper"
    And I click on "[data-drupal-selector=edit-field-c-n-components-0-subform] .horizontal-tab-button-1 a" element
    And I wait 1 second

    And I see the text "Title"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'][disabled]" element

    And I see the text "Content"
    And should see a "textarea[name='field_c_n_components[0][subform][field_c_p_content][0][value]']" element
    And should not see a "textarea[name='field_c_n_components[0][subform][field_c_p_content][0][value]'].required" element
    And should not see a "textarea[name='field_c_n_components[0][subform][field_c_p_content][0][value]'][disabled]" element

    And I see the text "Link above"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][uri]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][uri]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][uri]'][disabled]" element

    And should see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][title]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][title]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][title]'][disabled]" element

    And I see the text "Link below"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][uri]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][uri]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][uri]'][disabled]" element

    And should see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][title]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][title]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][title]'][disabled]" element

    And I see the text "Appearance"
    And I scroll to an element with id "edit-field-c-n-components-wrapper"
    And I click on "[data-drupal-selector=edit-field-c-n-components-0-subform] .horizontal-tab-button-2 a" element
    And I wait 1 second

    And I see the text "Theme"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_theme]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_theme]'][disabled]" element

    And I see the text "With space"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_space]']" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_space]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_space]'][disabled]" element

    And I see the text "Background"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_background][value]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_background][value]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_background][value]'][disabled]" element

    And I see the text "Column count"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_column_count]']" element
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_column_count]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_column_count]'][disabled]" element

    And I see the text "Fill width in the last row"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_fill_width][value]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_fill_width][value]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_fill_width][value]'][disabled]" element

    And I see the text "Display items as"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_list_item_view_as]']" element
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_list_item_view_as]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_list_item_view_as]'][disabled]" element

    And I see the text "Item theme"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_list_item_theme]']" element
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_list_item_theme]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_list_item_theme]'][disabled]" element
