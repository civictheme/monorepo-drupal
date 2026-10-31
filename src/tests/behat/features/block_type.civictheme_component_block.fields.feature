@p1 @civictheme @block_type @block_civictheme_component_block
Feature: Component block fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "block/add/civictheme_component_block"
    And I should see an ".field--name-field-c-b-components" element
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
