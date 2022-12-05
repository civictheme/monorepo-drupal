@p1 @civictheme @block_type @block_civictheme_component
Feature: Component block fields

  @api
  Scenario: Custom Block type appears in Block Type listing
    Given I am logged in as a user with the "Administrator" role
    When I visit "admin/structure/block/block-content/types"
    Then I should see the text "Component"

  @api
  Scenario: CivicTheme Component block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/block/block-content/manage/civictheme_component_block/fields"
    Then I should see the text "Components" in the "field_c_b_components" row

  @api
  Scenario: CivicTheme Component paragraph reference exists and works
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "block/add/civictheme_component_block"
    And I should see an "div.field--name-field-c-b-components" element
    And I should see an "input[value='Add Accordion']" element
    And I should see an "input[value='Add Attachment']" element
    And I should see an "input[value='Add Manual list']" element
    And I should see an "input[value='Add Callout']" element
    And I should see an "input[value='Add Content']" element
    And I should see an "input[value='Add iFrame']" element
    And I should see an "input[value='Add Automated list']" element
    And I should see an "input[value='Add Map']" element
    And I should see an "input[value='Add Promo']" element
    And I should see an "input[value='Add Slider']" element
    And I should see an "input[value='Add Webform']" element

