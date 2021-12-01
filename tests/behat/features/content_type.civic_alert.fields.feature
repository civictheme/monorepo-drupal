@civic @content_type @civic_alert
Feature: Fields on Civic alert content type

  Ensure that fields have been setup correctly.

  @api
  Scenario: Civic alert content type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civic_alert/fields"
    Then I should see the text "Date range" in the "field_c_n_date_range" row
    And I should see the text "Message" in the "field_c_n_body" row
    And I should see the text "Page visibility" in the "field_c_n_alert_page_visibility" row
    And I should see the text "Type" in the "field_c_n_alert_type" row

  @api
  Scenario: Civic alert content type page has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_alert"
    And the response status code should be 200
    Then I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element
    And I should see an "select[name='field_c_n_alert_type']" element
    And select "field_c_n_alert_type" should have an option "status"
    And select "field_c_n_alert_type" should have an option "success"
    And select "field_c_n_alert_type" should have an option "warning"
    And select "field_c_n_alert_type" should have an option "error"
    And I see field "field_c_n_date_range[0][value][date]"
    And I see field "field_c_n_date_range[0][end_value][date]"
    And I see field "field_c_n_alert_page_visibility[0][value]"
    And I see field "Message"
