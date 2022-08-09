@civictheme @paragraph @civictheme_listing
Feature: Tests the Listing paragraph

  Ensure that Listing paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Listing" in the "civictheme_listing" row

  @api
  Scenario: Listing paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civictheme_listing/fields"
    And I should see the text "field_c_p_content_type" in the "Content type" row
    And I should see the text "field_c_p_listing_f_exposed" in the "Filters" row
    And I should see the text "field_c_p_listing_limit" in the "Limit" row
    And I should see the text "field_c_p_limit_type" in the "Limit type" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_listing_multi_select" in the "Multi select" row
    And I should see the text "field_c_p_read_more" in the "Read more" row
    And I should see the text "field_c_p_show_filters" in the "Show filters" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_card_theme" in the "Card theme" row
    And I should see the text "field_c_p_background" in the "Background" row
    And I should see the text "field_c_p_space" in the "With space" row
    And I should see the text "field_c_p_topics" in the "Topics" row
    And I should see the text "field_c_p_view_as" in the "View as" row
    And I should see the text "field_c_p_space" in the "With space" row

  @api
  Scenario: CivicTheme Page field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields/node.civictheme_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Listing" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civictheme_listing_add_more" button
    And I wait for AJAX to finish
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_space]']" element
    And select "field_c_n_components[0][subform][field_c_p_space]" should have an option "None"
    And select "field_c_n_components[0][subform][field_c_p_space]" should have an option "Top"
    And select "field_c_n_components[0][subform][field_c_p_space]" should have an option "Bottom"
    And select "field_c_n_components[0][subform][field_c_p_space]" should have an option "Both"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][title]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_content_type]']" element
    Then select "field_c_n_components[0][subform][field_c_p_content_type]" should have an option "civictheme_page"
    Then select "field_c_n_components[0][subform][field_c_p_content_type]" should have an option "civictheme_event"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_topics][0][target_id]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_view_as]']" element
    Then select "field_c_n_components[0][subform][field_c_p_view_as]" should have an option "civictheme_promo_card"
    Then select "field_c_n_components[0][subform][field_c_p_view_as]" should have an option "civictheme_navigation_card"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_read_more][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_read_more][0][title]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    Then select "field_c_n_components[0][subform][field_c_p_theme]" should have an option "Light"
    Then select "field_c_n_components[0][subform][field_c_p_theme]" should have an option "Dark"
    And select "field_c_n_components[0][subform][field_c_p_card_theme]" should have an option "Light"
    And select "field_c_n_components[0][subform][field_c_p_card_theme]" should have an option "Dark"
    And I click on "div.paragraphs-subform .horizontal-tab-button-1 a" element
    And I wait 1 second
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_limit_type]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_listing_limit][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_listing_multi_select][value]']" element
    And I click on "div.paragraphs-subform .horizontal-tab-button-2 a" element
    And I wait 1 second
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_show_filters][value]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_listing_f_exposed]']" element
