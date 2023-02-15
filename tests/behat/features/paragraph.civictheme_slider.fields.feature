@p1 @civictheme @civictheme_slider
Feature: Slider, Slider Slide and Slider Slide Reference fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add Slider"

    # Slider fields.
    Then I should see an "[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'].required" element
    And should see an "[name='field_c_n_components[0][subform][field_c_p_theme]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_background][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_background][value]'].required" element

    # Slide fields.
    When I press "Add Slider slide"
    Then I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_title][0][value]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_title][0][value]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_image][media_library_selection]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_image_position]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_image_position]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_date][0][value][date]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_date][0][value][date]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_content][0][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_content][0][value]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_links][0][uri]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_links][0][uri]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_topics][0][target_id]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_topics][0][target_id]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][0][subform][field_c_p_theme]']" element

    # Slide reference fields.
    When I press "Add Slider reference slide"
    Then I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_reference][0][target_id]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_reference][0][target_id]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_link_text][0][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_link_text][0][value]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_image_position]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_image_position]'].required" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_slides][1][subform][field_c_p_theme]']" element
