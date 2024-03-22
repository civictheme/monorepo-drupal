@p1 @civictheme @civictheme_promo
Feature: Promo fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I press "Add Promo"

    Then should see an "[name='field_c_n_components[0][subform][field_c_p_title][0][value]']" element
    And should see an "[name='field_c_n_components[0][subform][field_c_p_title][0][value]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_title][0][value]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_content][0][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_content][0][value]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_content][0][value]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_link][0][uri]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_link][0][uri]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_link][0][uri]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_link][0][title]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_link][0][title]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_link][0][title]'][disabled]" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_theme]']" element

    And should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]']" element
    And should see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'].required" element
    And should not see an "[name='field_c_n_components[0][subform][field_c_p_vertical_spacing]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_background][value]']" element
