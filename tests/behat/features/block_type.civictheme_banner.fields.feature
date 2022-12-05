@p0 @civictheme @block_type @block_civictheme_banner
Feature: Banner block fields

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Banner"

  @api
  Scenario: CivicTheme banner block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civictheme_banner/fields"
    Then I should see the text "Theme" in the "field_c_b_theme" row
