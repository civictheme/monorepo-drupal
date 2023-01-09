@p0 @civictheme @block_type @block_civictheme_social_links
Feature: Social Links block fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "block/add/civictheme_social_links"
    Then the response status code should be 200
    And I should see an "[name='field_c_b_theme']" element
    And I should see an "[name='field_c_b_social_icons[0][subform][field_c_p_icon][media_library_selection]']" element
    And should see an "[name='field_c_b_social_icons[0][subform][field_c_p_link][0][uri]']" element
    And should see an "[name='field_c_b_social_icons[0][subform][field_c_p_link][0][uri]'].required" element
    And should see an "[name='field_c_b_social_icons[0][subform][field_c_p_link][0][title]']" element
    And should see an "[name='field_c_b_social_icons[0][subform][field_c_p_link][0][title]'].required" element
