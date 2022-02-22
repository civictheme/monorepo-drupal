@civic @block_type @block_civic_mobile_navigation
Feature: Tests the Civic Mobile Navigation

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Mobile Navigation"

  @api
  Scenario: Civic Component block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civic_mobile_navigation/fields"
    Then I should see the text "Bottom menu" in the "field_c_b_bottom_menu" row
    Then I should see the text "Top menu" in the "field_c_b_top_menu" row
    Then I should see the text "Theme" in the "field_c_b_theme" row
    Then I should see the text "Trigger text" in the "field_c_b_trigger_text" row
    Then I should see the text "Trigger theme" in the "field_c_b_trigger_theme" row
