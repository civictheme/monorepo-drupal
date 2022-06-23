@civictheme @block_type @block_civictheme_search
Feature: Tests the CivicTheme Search block Type

  Ensure that CivicTheme Search block fields have been setup correctly.

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Search"

  @api
  Scenario: CivicTheme search block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civictheme_search/fields"
    Then I should see the text "Link" in the "field_c_b_link" row
