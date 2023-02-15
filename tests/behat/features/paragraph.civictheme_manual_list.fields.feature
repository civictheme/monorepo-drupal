@p1 @civictheme @civictheme_manual_list
Feature: Manual list fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add Manual list"

    And I see the text "Content"

    And I see the text "List items"
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_event_card_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_event_card_ref_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_navigation_card_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_navigation_card_ref_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_promo_card_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_promo_card_ref_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_publication_card_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_service_card_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_subject_card_add_more']" element
    And should see a "[name='field_c_n_components_0_subform_field_c_p_list_items_civictheme_subject_card_ref_add_more']" element

    And I see the text "Title"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_title][0][value]]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_title][0][value]'][disabled]" element

    And I see the text "Content"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_content][0][value]']" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_content][0][value]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_content][0][value]'][disabled]" element

    And I see the text "Link above"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][uri]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_link_above][0][title]']" element

    And I see the text "Link below"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][uri]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_link_below][0][title]']" element

    And I see the text "Appearance"

    And I see the text "Theme"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_theme]']" element

    And I see the text "Vertical spacing"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'][disabled]" element

    And I see the text "Background"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_background][value]']" element

    And I see the text "Column count"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_column_count]']" element
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_column_count]'].required" element
    And should not see a "[name='field_c_n_components[0][subform][field_c_p_list_column_count]'][disabled]" element

    And I see the text "Fill width in the last row"
    And should see a "[name='field_c_n_components[0][subform][field_c_p_list_fill_width][value]']" element
