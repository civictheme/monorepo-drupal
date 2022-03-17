@civic @block_type @block_civic_banner
Feature: Tests the Civic Banner block Type

  Ensure that Civic Banner block fields have been setup correctly.

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Banner"

  @api
  Scenario: Civic banner block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civic_banner/fields"
    Then I should see the text "Theme" in the "field_c_b_theme" row
