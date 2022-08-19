@civictheme @content_type @civictheme_alert
Feature: Fields on CivicTheme Alert content type

  Ensure that fields have been setup correctly.

  @api
  Scenario: CivicTheme Alert content type exists with fields
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_alert/fields"
    Then I should see the text "Date range" in the "field_c_n_date_range" row
    And I should see the text "Message" in the "field_c_n_body" row
    And I should see the text "Page visibility" in the "field_c_n_alert_page_visibility" row
    And I should see the text "Type" in the "field_c_n_alert_type" row

  @api
  Scenario: CivicTheme Alert content type form has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_alert"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element

    And I see field "Message"
    And should see a "textarea#edit-field-c-n-body-0-value" element
    And should see a "textarea#edit-field-c-n-body-0-value.required" element
    And should not see a "textarea#edit-field-c-n-body-0-value[disabled]" element

    And I see field "Type"
    And should see a "select#edit-field-c-n-alert-type" element
    And should see a "select#edit-field-c-n-alert-type.required" element
    And should not see a "select#edit-field-c-n-alert-type[disabled]" element

    And I should see the text "Start date"
    And I should see a "input#edit-field-c-n-date-range-0-value-date" element
    And I should see a "input#edit-field-c-n-date-range-0-value-date.required" element
    And I should not see a "input#edit-field-c-n-date-range-0-value-date.required[disabled]" element
    And I should see the text "End date"
    And I should see a "input#edit-field-c-n-date-range-0-end-value-date" element
    And I should see a "input#edit-field-c-n-date-range-0-end-value-date.required" element
    And I should not see a "input#edit-field-c-n-date-range-0-end-value-date.required[disabled]" element

    And I see field "Page visibility"
    And should see a "textarea#edit-field-c-n-alert-page-visibility-0-value" element
    And should not see a "textarea#edit-field-c-n-alert-page-visibility-0-value.required" element
    And should not see a "textarea#edit-field-c-n-alert-page-visibility-0-value[disabled]" element

    And I see field "Published"
    And I should see a "input#edit-status-value" element
    And I should not see a "input#edit-status-value.required" element
    And I should not see a "input#edit-status-value[disabled]" element
