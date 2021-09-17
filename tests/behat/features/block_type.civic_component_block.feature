@block_type @civic_component_block
Feature: Tests the Civic Component Block Type

  Ensure that Civic Component Block fields have been setup correctly.

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Component Block"

  @api
  Scenario: Component Block type exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/block/block-content/manage/civic_component_block/fields"
    Then I should see the text "Components" in the "field_b_components" row

  @api
  Scenario: Component Block paragraph reference exists and works
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "block/add/civic_component_block"
    And I should see an "div.field--name-field-b-components" element
    And I should see an "input[value='Add Card container']" element
    And I should see an "input[value='Add Callout']" element
    And I should see an "input[value='Add Attachment']" element
    And I should see an "input[value='Add Map']" element

