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
    And I should see the text "field_c_p_background" in the "Background" row
    And I should see the text "field_c_p_listing_content_type" in the "Content type" row
    And I should see the text "field_c_p_listing_item_view_as" in the "Display items as" row
    And I should see the text "field_c_p_listing_filters_exp" in the "Exposed filters" row
    And I should see the text "field_c_p_listing_item_theme" in the "Item theme" row
    And I should see the text "field_c_p_listing_limit" in the "Limit" row
    And I should see the text "field_c_p_listing_limit_type" in the "Limit type" row
    And I should see the text "field_c_p_listing_link_above" in the "Read more above" row
    And I should see the text "field_c_p_listing_link_below" in the "Read more below" row
    And I should see the text "field_c_p_listing_show_filters" in the "Show filters" row
    And I should see the text "field_c_p_listing_site_sections" in the "Site sections" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_listing_topics" in the "Topics" row
    And I should see the text "field_c_p_space" in the "With space" row
    And I should see the text "field_c_p_listing_type" in the "Listing type" row

  @api @javascript
  Scenario: Listing paragraph fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"

    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait 1 second
    And I press the "field_c_n_components_civictheme_listing_add_more" button
    And I wait for AJAX to finish

    And I see the text "Selection"

    And I see the text "Content type"
    And should see a "input#edit-field-c-n-topics-0-target-id" element
    And should not see a "input#edit-field-c-n-components-0-subform-field-c-p-listing-content-type--xPcCPqBVt2U--wrapper.required" element
    And should not see a "input#edit-field-c-n-components-0-subform-field-c-p-listing-content-type--xPcCPqBVt2U--wrapper[disabled]" element

    And I click on "[data-drupal-selector=edit-field-c-n-components-widget-0-subform-group-metadata] summary" element
    And I wait 1 second

    And I see the text "Topics"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_topics][0][target_id]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_topics][0][target_id]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_topics][0][target_id]'][disabled]" element

    And I see the text "Site sections"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_site_sections][0][target_id]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_site_sections][0][target_id]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_site_sections][0][target_id]'][disabled]" element

    And I click on "[data-drupal-selector=edit-field-c-n-components-widget-0-subform-group-limit] summary" element
    And I wait 1 second

    And I see the text "Limit type"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_limit_type]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_limit_type]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_limit_type]'][disabled]" element

    And I see the text "Limit"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_limit][0][value]']" element
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_limit][0][value]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_limit][0][value]'][disabled]" element

    And I click on "[data-drupal-selector=edit-field-c-n-components-widget-0-subform-group-filters] summary" element
    And I wait 1 second

    And I see the text "Show filters"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_show_filters][value]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_show_filters][value]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_show_filters][value]'][disabled]" element

    And I see the text "Exposed filters"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_listing_filters_exp]']" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_listing_filters_exp]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_listing_filters_exp]'][disabled]" element

    And I see the text "Fields"
    And I scroll to an element with id "edit-field-c-n-components-wrapper"
    And I click on "[data-drupal-selector=edit-field-c-n-components-0-subform] .horizontal-tab-button-1 a" element
    And I wait 1 second

    And I see the text "Title"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_title][0][value]'][disabled]" element

    And I see the text "Read more above"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_above][0][uri]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_above][0][uri]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_above][0][uri]'][disabled]" element

    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_above][0][title]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_above][0][title]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_above][0][title]'][disabled]" element

    And I see the text "Read more below"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_below][0][uri]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_below][0][uri]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_below][0][uri]'][disabled]" element

    And should see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_below][0][title]']" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_below][0][title]'].required" element
    And should not see a "input[name='field_c_n_components[0][subform][field_c_p_listing_link_below][0][title]'][disabled]" element

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

    And I see the text "Display items as"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_listing_item_view_as]']" element
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_listing_item_view_as]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_listing_item_view_as]'][disabled]" element

    And I see the text "Item theme"
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_listing_item_theme]']" element
    And should see a "select[name='field_c_n_components[0][subform][field_c_p_listing_item_theme]'].required" element
    And should not see a "select[name='field_c_n_components[0][subform][field_c_p_listing_item_theme]'][disabled]" element
