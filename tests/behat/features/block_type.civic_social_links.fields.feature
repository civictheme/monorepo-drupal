@civic @block_type @block_civic_social_links
Feature: Tests the Civic Social Links block Type

  Ensure that Civic Social Links block fields have been setup correctly.

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Social Links"

  @api
  Scenario: Civic banner block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civic_social_links/fields"
    Then I should see the text "Social links" in the "field_c_b_social_icons" row
    And I should see the text "Theme" in the "field_c_b_theme" row
    And I should see the text "With border" in the "field_c_b_with_border" row
