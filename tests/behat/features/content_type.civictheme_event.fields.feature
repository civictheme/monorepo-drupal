@civictheme @content_type @civictheme_event
Feature: Fields on Page content type

  Ensure that Review fields have been setup correctly.

  Background:
    Given civictheme_event content:
      | title                   | status    |
      | [TEST] Test Event title | published |

  @api
  Scenario: Event content type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_event/fields"
    Then I should see the text "Attachments" in the "field_c_n_attachments" row
    And I should see the text "Body" in the "field_c_n_body" row
    And I should see the text "Custom Last updated date" in the "field_c_n_custom_last_updated" row
    And I should see the text "Date" in the "field_c_n_date" row
    And I should see the text "Location" in the "field_c_n_location" row
    And I should see the text "how Last updated date" in the "field_c_n_show_last_updated" row
    And I should see the text "Show Table of Contents" in the "field_c_n_show_toc" row
    And I should see the text "Summary" in the "field_c_n_summary" row
    And I should see the text "Thumbnail" in the "field_c_n_thumbnail" row
    And I should see the text "Topics" in the "field_c_n_topics" row

  @api
  Scenario: Event content type page has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_event"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element

    And I see field "Tagline"
    And I see field "field_c_n_body[0][value]"
    And I see field "field_c_n_show_toc[value]"
    And I should see an "input[name='field_c_n_location_civictheme_map_add_more']" element
    And I should see an "input[name='field_c_n_attachments_civictheme_attachment_add_more']" element
    And I should see an "input[name='field_c_n_thumbnail-media-library-open-button']" element
    And I should see an "input[name='field_c_n_image-media-library-open-button']" element
    And I should see an "input[name='field_c_n_more_information_civictheme_promo_add_more']" element
    And I see field "field_c_n_date[0][value][date]"
    And I see field "field_c_n_date[0][end_value][date]"
