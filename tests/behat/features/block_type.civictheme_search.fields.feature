@p1 @civictheme @block_type @block_civictheme_search
Feature: Search block fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "block/add/civictheme_search"
    Then the response status code should be 200
    And should see an "[name='field_c_b_link[0][uri]']" element
    And should see an "[name='field_c_b_link[0][uri]'].required" element
    And should see an "[name='field_c_b_link[0][title]']" element
    And should see an "[name='field_c_b_link[0][title]'].required" element
