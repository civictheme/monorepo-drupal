@p0 @civictheme @civictheme_subject_card_ref
Feature: Subject reference card fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add Manual list"
    And I press "Add Subject reference card"

    Then I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_reference][0][target_id]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_reference][0][target_id]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_reference][0][target_id]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_theme]']" element
