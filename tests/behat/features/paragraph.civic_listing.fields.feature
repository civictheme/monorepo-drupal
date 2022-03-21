@civic @paragraph @civic_listing
Feature: Tests the Listing paragraph

  Ensure that Listing paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Listing" in the "civic_listing" row

  @api
  Scenario: Listing paragraph exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_listing/fields"
    And I should see the text "field_c_p_content_type" in the "Content type" row
    And I should see the text "field_c_p_listing_f_exposed" in the "Filters" row
    And I should see the text "field_c_p_hide_count" in the "Hide count" row
    And I should see the text "field_c_p_listing_limit" in the "Limit" row
    And I should see the text "field_c_p_limit_type" in the "Limit type" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_listing_multi_select" in the "Multi select" row
    And I should see the text "field_c_p_read_more" in the "Read more" row
    And I should see the text "field_c_p_show_filters" in the "Show filters" row
    And I should see the text "field_c_p_show_pager" in the "Show pager" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_topics" in the "Topics" row
    And I should see the text "field_c_p_view_as" in the "View as" row
    And I should see the text "field_c_p_space" in the "With space" row

  @api
  Scenario: Civic Page field_c_n_components fields settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civic_page/fields/node.civic_page.field_c_n_components"
    And the "Label" field should contain "Components"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Listing" checkbox should be checked

  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civic_listing_add_more" button
    And I wait for AJAX to finish
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_space]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_link][0][title]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_content_type]']" element
    Then select "field_c_n_components[0][subform][field_c_p_content_type]" should have an option "civic_page"
    Then select "field_c_n_components[0][subform][field_c_p_content_type]" should have an option "civic_event"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_topics][0][target_id]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_view_as]']" element
    Then select "field_c_n_components[0][subform][field_c_p_view_as]" should have an option "civic_card_promo"
    Then select "field_c_n_components[0][subform][field_c_p_view_as]" should have an option "civic_card_navigation"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_read_more][0][uri]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_read_more][0][title]']" element
    And I click on "div.paragraphs-subform .horizontal-tab-button-1 a" element
    And I wait 1 second
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_limit_type]']" element
    Then select "field_c_n_components[0][subform][field_c_p_limit_type]" should have an option "limited"
    Then select "field_c_n_components[0][subform][field_c_p_limit_type]" should have an option "unlimited"
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_listing_limit][0][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_listing_multi_select][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_hide_count][value]']" element
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_show_pager][value]']" element
    And I click on "div.paragraphs-subform .horizontal-tab-button-2 a" element
    And I wait 1 second
    And I should see an "input[name='field_c_n_components[0][subform][field_c_p_show_filters][value]']" element
    And I should see an "select[name='field_c_n_components[0][subform][field_c_p_listing_f_exposed]']" element
