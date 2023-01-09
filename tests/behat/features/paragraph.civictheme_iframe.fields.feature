@p1 @civictheme @civictheme_iframe
Feature: IFrame fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add iFrame"

    Then should see an "[name='field_c_n_components[0][subform][field_c_p_url][0][value]']" element
    And should see an "[name='field_c_n_components[0][subform][field_c_p_url][0][value]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_url][0][value]'][disabled]" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_width][0][value]']" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_width][0][value]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_width][0][value]'][disabled]" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_height][0][value]']" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_height][0][value]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_height][0][value]'][disabled]" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_attributes][0][value]']" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_attributes][0][value]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_attributes][0][value]'][disabled]" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_theme]']" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_background][value]']" element

