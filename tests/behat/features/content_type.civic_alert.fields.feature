@civic @content_type @civic_alert
Feature: Fields on Civic alert content type

  Ensure that fields have been setup correctly.

  Background:
    Given civic_alert content:
      | nid    | title                                 | status | field_c_n_alert_type | field_c_n_alert_page_visibility | body         |
      | 999991 | [TEST] Test alert title Homepage only | 1      | status               | /                               | Test message |
      | 999992 | [TEST] Test alert title               | 1      | error                |                                 | Test message |
      | 999993 | [TEST] Test dismissing alert          | 1      | error                |                                 | Test message |

    Given civic_page content:
      | title                       | status |
      | [TEST] Test alerts on pages | 1      |

  @api
  Scenario: Civic alert content type exists with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civic_alert/fields"
    Then I should see the text "Date range" in the "field_c_n_date_range" row
    Then I should see the text "Message" in the "body" row
    Then I should see the text "Page visibility" in the "field_c_n_alert_page_visibility" row
    Then I should see the text "Type" in the "field_c_n_alert_type" row

  @api
  Scenario: Civic alert content type page has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_alert"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element
    And I should see an "select[name='field_c_n_alert_type']" element
    Then select "field_c_n_alert_type" should have an option "status"
    Then select "field_c_n_alert_type" should have an option "success"
    Then select "field_c_n_alert_type" should have an option "warning"
    Then select "field_c_n_alert_type" should have an option "error"
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

  @api @javascript @skipped
  Scenario: Civic alerts should be dismissed and not show in same session
    Given I am an anonymous user
    When I visit "civic_page" "[TEST] Test alerts on pages"
    Then I should see the text "[TEST] Test dismissing alert"
    And I press "dismiss-alert-999993"
    And I should not see the text "[TEST] Test dismissing alert"
    # Revisit same page - the banner should remain dismissed.
    And I visit "civic_page" "[TEST] Test alerts on pages"
    And I should not see the text "[TEST] Test dismissing alert"
    # New session the alert should be visible.
    Given I am an anonymous user
    When I visit "civic_page" "[TEST] Test alerts on pages"
    Then I should see the text "[TEST] Test dismissing alert"

  @api @javascript @skipped
  Scenario: Civic alerts should be dismissed and not show in same session for logged in user.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "civic_page" "[TEST] Test alerts on pages"
    Then I should see the text "[TEST] Test dismissing alert"
    And I press "dismiss-alert-999993"
    And I should not see the text "[TEST] Test dismissing alert"
    # Revisit same page - the alert should remain dismissed.
    And I visit "civic_page" "[TEST] Test alerts on pages"
    And I should not see the text "[TEST] Test dismissing alert"
    # New session the alert should be visible.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "civic_page" "[TEST] Test alerts on pages"
    Then I should see the text "[TEST] Test dismissing alert"

