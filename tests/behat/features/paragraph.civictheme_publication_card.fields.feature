@p0 @civictheme @civictheme_card @civictheme_publication_card
Feature: Publication card fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page fields"
    When I press "Add Manual list"
    And I press "Add Publication card"

    Then I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_title][0][value]']" element
    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_title][0][value]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_title][0][value]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_image][media_library_selection]']" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_summary][0][value]']" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_summary][0][value]'].required" element
    And I should not see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_summary][0][value]'][disabled]" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_document][media_library_selection]']" element

    And I should see an "[name='field_c_n_components[0][subform][field_c_p_list_items][0][subform][field_c_p_theme]']" element
