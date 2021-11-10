@civic @content_type @civic_alert
Feature: Fields on Civic alert content type

  Ensure that fields have been setup correctly.

  Background:
    Given civic_alert content:
      | title                                  | status    | field_c_n_type | field_c_n_alert_page_visibility | field_c_n_message |
      | [TEST] Test alert title Homepage only  | 1         | status         |   /                             | Test message      |
      | [TEST] Test alert title                | 1         | error          |                                 | Test message      |

    Given civic_page content:
      | title                        | status    |
      | [TEST] Test alerts on pages  | 1         |

  @api
  Scenario: Civic alert content type exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/types/manage/civic_alert/fields"
    Then I should see the text "Date range" in the "field_c_n_date_range" row
    Then I should see the text "Message" in the "field_c_n_message" row
    Then I should see the text "Page visibility" in the "field_c_n_alert_page_visibility" row
    Then I should see the text "Type" in the "field_c_n_type" row

  @api
  Scenario: Civic alert content type page has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_alert"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element
    And I should see an "select[name='field_c_n_type']" element
    Then select "field_c_n_type" should have an option "status"
    Then select "field_c_n_type" should have an option "success"
    Then select "field_c_n_type" should have an option "warning"
    Then select "field_c_n_type" should have an option "error"
    And I see field "field_c_n_date_range[0][value][date]"
    And I see field "field_c_n_date_range[0][end_value][date]"
    And I see field "field_c_n_alert_page_visibility[0][value]"

  @api @javascript
  Scenario: Civic alerts can be viewed on homepage
    Given I am an anonymous user
    And I go to the homepage
    And wait 5 second
    And I wait for AJAX to finish
    And I should see the text "[TEST] Test alert title Homepage only"
    And I should see the text "[TEST] Test alert title"

  @api @javascript
  Scenario: Civic alerts should follow the visibility settings.
    Given I am an anonymous user
    When I visit "civic_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    And I should not see the text "[TEST] Test alert title Homepage only"
    And I should see the text "[TEST] Test alert title"
