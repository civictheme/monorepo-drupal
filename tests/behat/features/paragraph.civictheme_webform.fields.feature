@p1 @civictheme @civictheme_webform
Feature: Webform fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add Webform"

    Then I should see an "[name='field_c_n_components[0][subform][field_c_p_webform][0][target_id]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_webform][0][target_id]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_webform][0][target_id]'][disabled]" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_theme]']" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_background][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_background][value]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_background][value]'][disabled]" element
