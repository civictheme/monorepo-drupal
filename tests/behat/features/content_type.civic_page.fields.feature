@content_type @civic_page
Feature: Fields on Page content type

  Ensure that Review fields have been setup correctly.

  Background:
    Given civic_page content:
      | title                    | status           |
      | [TEST] Test review title | published        |

  @api
  Scenario: Civic banner block type exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/types/manage/civic_page/fields"
    Then I should see the text "Banner background" in the "field_c_n_banner_background" row
    Then I should see the text "Banner components" in the "field_c_n_banner_components" row
    Then I should see the text "Banner type" in the "field_c_n_banner_type" row
    Then I should see the text "Components" in the "field_c_n_components" row
    Then I should see the text "Featured banner image" in the "field_c_n_featured_banner_image" row

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
    And should see an "input[name='field_c_n_banner_components_civic_content_add_more']" element

    And I should see text matching "Components"
    And should see an "input[name='field_c_n_components_civic_content_add_more']" element
    And should see an "input[name='field_c_n_components_civic_card_container_add_more']" element

    And should see an "input[name='field_c_n_banner_background-media-library-open-button']" element
    And should see an "input[name='field_c_n_featured_banner_image-media-library-open-button']" element
    And I should see text matching "Banner type"
    And should see an "select[name='field_c_n_banner_type']" element
