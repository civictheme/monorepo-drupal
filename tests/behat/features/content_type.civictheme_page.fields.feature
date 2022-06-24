@civictheme @content_type @civictheme_page
Feature: Fields on Page content type

  Ensure that Review fields have been setup correctly.

  Background:
    Given civictheme_page content:
      | title                    | status    |
      | [TEST] Test review title | published |

  @api
  Scenario: CivicTheme banner block type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields"
    Then I should see the text "Banner background" in the "field_c_n_banner_background" row
    Then I should see the text "Banner components" in the "field_c_n_banner_components" row
    Then I should see the text "Banner type" in the "field_c_n_banner_type" row
    Then I should see the text "Banner components" in the "field_c_n_banner_components" row
    Then I should see the text "Banner components bottom" in the "field_c_n_banner_components_bott" row
    Then I should see the text "Banner featured image" in the "field_c_n_banner_featured_image" row
    Then I should see the text "Components" in the "field_c_n_components" row
    Then I should see the text "Show Table of Contents" in the "field_c_n_show_toc" row
    Then I should see the text "Summary" in the "field_c_n_summary" row
    Then I should see the text "Thumbnail" in the "field_c_n_thumbnail" row
    Then I should see the text "Topics" in the "field_c_n_topics" row
    Then I should see the text "With space" in the "field_c_n_space" row

  @api
  Scenario: Page content type page has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element

    And I should see text matching "Banner components"
    And should see an "input[name='field_c_n_banner_components_civictheme_content_add_more']" element

    And I should see text matching "Banner components bottom"
    And should see an "input[name='field_c_n_banner_components_bott_civictheme_content_add_more']" element

    And should see an "input[name='field_c_n_banner_background-media-library-open-button']" element
    And should see an "input[name='field_c_n_banner_featured_image-media-library-open-button']" element
    And I should see text matching "Banner type"
    And should see an "input[name='field_c_n_banner_type']" element

    And I should see text matching "Components"
    And should see an "input[name='field_c_n_components_civictheme_content_add_more']" element
    And should see an "input[name='field_c_n_components_civictheme_card_container_add_more']" element

    And I should see text matching "With space"
    And should see an "select[name='field_c_n_space']" element

    And I should see text matching "Hide sidebar"
    And should see an "input[name='field_c_n_hide_sidebar[value]']" element

    And I should see text matching "Show last updated date"
    And should see an "input[name='field_c_n_show_last_updated[value]']" element

    And I should see text matching "Show Table of Contents"
    And should see an "input[name='field_c_n_show_toc[value]']" element

    And I should see text matching "Topics"
    And I should see an "input[name='field_c_n_topics[0][target_id]']" element
    And I should see an "input[name='field_c_n_thumbnail-media-library-open-button']" element
