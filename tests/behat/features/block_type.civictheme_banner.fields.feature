@p0 @civictheme @block_type @block_civictheme_banner
Feature: Banner block fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "block/add/civictheme_banner"
    Then I should see an "[name='field_c_b_banner_type']" element
    And I should see an "[name='field_c_b_banner_type'].required" element
    And I should see an "[name='field_c_b_theme']" element
    And I should see an "[name='field_c_b_background_image[media_library_selection]']" element
    And I should see an "[name='field_c_b_blend_mode']" element
    And I should not see an "[name='field_c_b_blend_mode'].required" element
    And I should see an "[name='field_c_b_featured_image[media_library_selection]']" element
