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

  @api
  Scenario: CivicTheme search block type has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "block/add/civictheme_search"
    Then the response status code should be 200

    And I see field "Block description"
    And should see an "input[name='field_c_b_link[0][uri]']" element
    And should see an "input[name='field_c_b_link[0][uri]'].required" element
    And should see an "input[name='field_c_b_link[0][title]']" element
