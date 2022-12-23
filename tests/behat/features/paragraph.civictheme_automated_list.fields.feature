@p0 @civictheme @civictheme_automated_list
Feature: Automated list fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add Automated list"

    And I see the text "Content"

    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_type]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_type]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_type]'][disabled]" element

    And I see the text "Content type"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_content_type]']" element

    And I see the text "Topics"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_topics][0][target_id]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_topics][0][target_id]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_topics][0][target_id]'][disabled]" element

    And I see the text "Site sections"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_site_sections][0][target_id]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_site_sections][0][target_id]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_site_sections][0][target_id]'][disabled]" element

    And I see the text "Limit type"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_limit_type]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_limit_type]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_limit_type]'][disabled]" element

    And I see the text "Limit"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_limit][0][value]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_limit][0][value]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_limit][0][value]'][disabled]" element

    And I see the text "Exposed filters"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_filters_exp][type]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_filters_exp][topic]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_filters_exp][title]']" element

    And I see the text "Fields"

    And I see the text "Title"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_title][0][value]]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_title][0][value]'][disabled]" element

    And I see the text "Content"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_content][0][value]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_content][0][value]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_content][0][value]'][disabled]" element

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

    And I see the text "Theme"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_theme]']" element

    And I see the text "Vertical spacing"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'][disabled]" element

    And I see the text "Background"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_background][value]']" element

    And I see the text "Column count"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_column_count]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_column_count]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_column_count]'][disabled]" element

    And I see the text "Fill width in the last row"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_fill_width][value]']" element

    And I see the text "Display items as"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_item_view_as]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_item_view_as]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_item_view_as]'][disabled]" element

    And I see the text "Item theme"
    And should see a "input[name='field_c_n_components[0][subform][field_c_p_list_item_theme]']" element
