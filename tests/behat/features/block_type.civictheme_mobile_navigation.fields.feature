@p0 @civictheme @block_type @block_civictheme_mobile_navigation
Feature: Mobile Navigation block fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Administrator" role
    When I go to "block/add/civictheme_mobile_navigation"
    Then I should see an "[name='field_c_b_top[0][target_id]']" element
    And I should not see an "[name='field_c_b_top[0][target_id]'].required" element
    And I should see an "[name='field_c_b_bottom[0][target_id]']" element
    And I should not see an "[name='field_c_b_bottom[0][target_id]'].required" element
    And I should see an "[name='field_c_b_theme']" element
    And I should see an "[name='field_c_b_trigger_text[0][value]']" element
    And I should see an "[name='field_c_b_trigger_text[0][value]'].required" element
    And I should see an "[name='field_c_b_trigger_theme']" element
    And I should see an "[name='field_c_b_trigger_theme'].required" element
