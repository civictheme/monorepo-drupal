@civictheme @content_type @civictheme_event
Feature: Fields on CivicTheme Event content type

  Ensure that fields have been setup correctly.

  @api
  Scenario: CivicTheme Event content type exists with fields
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
  Scenario: CivicTheme Event content type form has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_event"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element

    And I see field "Summary"
    And should see a "textarea#edit-field-c-n-summary-0-value" element
    And should not see a "textarea#edit-field-c-n-summary-0-value.required" element
    And should not see a "textarea#edit-field-c-n-summary-0-value[disabled]" element

    And I see field "Topics"
    And should see a "input#edit-field-c-n-topics-0-target-id" element
    And should not see a "input#edit-field-c-n-topics-0-target-id.required" element
    And should not see a "input#edit-field-c-n-topics-0-target-id[disabled]" element

    And I should see the text "Thumbnail"
    And should see a "input#edit-field-c-n-thumbnail-open-button" element
    And should not see a "input#edit-field-c-n-thumbnail-open-button.required" element
    And should not see a "input#edit-field-c-n-thumbnail-open-button[disabled]" element

    And I should see the text "Start date"
    And I should see a "input#edit-field-c-n-date-0-value-date" element
    And I should not see a "input#edit-field-c-n-date-0-value-date.required" element
    And I should not see a "input#edit-field-c-n-date-0-value-date.required[disabled]" element
    And I should see the text "End date"
    And I should see a "input#edit-field-c-n-date-0-end-value-date" element
    And I should not see a "input#edit-field-c-n-date-0-end-value-date.required" element
    And I should not see a "input#edit-field-c-n-date-0-end-value-date.required[disabled]" element

    And I see field "Address"
    And I should see a "input#edit-field-c-n-location-0-subform-field-c-p-address-0-value" element

    And I see field "Show Table of Contents"
    And I should see a "input#edit-field-c-n-show-toc-value" element
    And I should not see a "input#edit-field-c-n-show-toc-value.required" element
    And I should not see a "input#edit-field-c-n-show-toc-value[disabled]" element

    And I see field "Body"
    And should see a "textarea#edit-field-c-n-body-0-value" element
    And should see a "textarea#edit-field-c-n-body-0-value.required" element
    And should not see a "textarea#edit-field-c-n-body-0-value[disabled]" element

    And I see field "Show Last updated date"
    And I should see a "input#edit-field-c-n-show-last-updated-value" element
    And I should not see a "input#edit-field-c-n-show-last-updated-value.required" element
    And I should not see a "input#edit-field-c-n-show-last-updated-value[disabled]" element

    And I should see the text "Custom Last updated date"
    And I should see a "input#edit-field-c-n-custom-last-updated-0-value-date" element
    And I should not see a "input#edit-field-c-n-custom-last-updated-0-value-date.required" element
    And I should not see a "input#edit-field-c-n-custom-last-updated-0-value-date[disabled]" element

    And I see field "Published"
    And I should see a "input#edit-status-value" element
    And I should not see a "input#edit-status-value.required" element
    And I should not see a "input#edit-status-value[disabled]" element
