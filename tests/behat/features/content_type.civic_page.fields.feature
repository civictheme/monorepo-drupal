@content_type @civic_page
Feature: Fields on Page content type

  Ensure that Review fields have been setup correctly.

  Background:
    Given civic_page content:
      | title                    | status           |
      | [TEST] Test review title | published        |

  @api
  Scenario: Page content type page has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element

    And I should see text matching "Banner components"
    And should see an "input[name='field_n_banner_components_civic_content_add_more']" element

    And I should see text matching "Components"
    And should see an "input[name='field_n_components_civic_content_add_more']" element
