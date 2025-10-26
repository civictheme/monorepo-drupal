@p1 @civictheme @content_type @civictheme_alert
Feature: CivicTheme Alert content type render

  Background:
    Given civictheme_alert content:
      | nid    | title                                        | status | field_c_n_alert_type | field_c_n_alert_page_visibility | field_c_n_body:value                        | field_c_n_body:format | field_c_n_date_range:value      | field_c_n_date_range:end_value   |
      | 999991 | [TEST] Test alert title Homepage only        | 1      | information          | /                               | [TEST] Test alert body Homepage only        | civictheme_rich_text  | [relative:-1 day#Y-m-d\TH:i:s]  | [relative:+10 days#Y-m-d\TH:i:s] |
      | 999992 | [TEST] Test alert title all pages            | 1      | error                |                                 | [TEST] Test alert body all pages            | civictheme_rich_text  | [relative:-1 day#Y-m-d\TH:i:s]  | [relative:+10 days#Y-m-d\TH:i:s] |
      | 999993 | [TEST] Test dismissing alert title all pages | 1      | error                |                                 | [TEST] Test dismissing alert body all pages | civictheme_rich_text  | [relative:-1 day#Y-m-d\TH:i:s]  | [relative:+10 days#Y-m-d\TH:i:s] |
      | 999994 | [TEST] Test alert title all pages future     | 1      | error                |                                 | [TEST] Test alert body all pages future     | civictheme_rich_text  | [relative:+2 days#Y-m-d\TH:i:s] | [relative:+10 days#Y-m-d\TH:i:s] |
      | 999995 | [TEST] Test alert title visibility           | 1      | error                |                                 | [TEST] Test alert body visibility           | civictheme_rich_text  | [relative:-1 day#Y-m-d\TH:i:s]  | [relative:+10 days#Y-m-d\TH:i:s] |

    Given civictheme_page content:
      | title                        | status |
      | [TEST] Test alerts on pages  | 1      |
      | [TEST] Test alert visibility | 1      |

  @api @javascript
  Scenario: Alerts can be viewed on homepage
    Given I am an anonymous user
    And I go to the homepage
    And wait 5 second
    And I wait for AJAX to finish
    And I should see the text "[TEST] Test alert title Homepage only"
    And I should see the text "[TEST] Test alert body Homepage only"
    And I should see the text "[TEST] Test alert title all pages"
    And I should see the text "[TEST] Test alert body all pages"
    And I should see the text "[TEST] Test dismissing alert title all pages"
    And I should see the text "[TEST] Test dismissing alert body all pages"

  @api @javascript
  Scenario: Alerts should follow the visibility settings
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should not see the text "[TEST] Test alert body Homepage only"
    And I should see the text "[TEST] Test alert body all pages"
    And I should see the text "[TEST] Test dismissing alert body all pages"

  @api @javascript
  Scenario: Alerts should be dismissed and not shown in the same session
    Given I am an anonymous user
    And I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    And I should not see the text "[TEST] Test alert body Homepage only"
    And I should see the text "[TEST] Test alert body all pages"
    And I should see the text "[TEST] Test dismissing alert body all pages"
    When I press "dismiss-alert-999993"
    Then I should not see the text "[TEST] Test alert body Homepage only"
    And I should see the text "[TEST] Test alert body all pages"
    And I should not see the text "[TEST] Test dismissing alert body all pages"
    # Revisit same page - the banner should remain dismissed.
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 3 seconds
    Then I should not see the text "[TEST] Test alert body Homepage only"
    And I should see the text "[TEST] Test alert body all pages"
    And I should not see the text "[TEST] Test dismissing alert body all pages"
    # New session - the alert should be visible.
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should not see the text "[TEST] Test alert body Homepage only"
    And I should see the text "[TEST] Test alert body all pages"
    And I should see the text "[TEST] Test dismissing alert body all pages"

  @api @javascript
  Scenario: Alerts should be dismissed and not shown in same session for logged in user
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test dismissing alert body all pages"
    And I should see the text "[TEST] Test alert body all pages"
    And I press "dismiss-alert-999993"
    And I should not see the text "[TEST] Test dismissing alert body all pages"
    # Revisit same page - the alert should remain dismissed.
    And I visit "civictheme_page" "[TEST] Test alerts on pages"
    And I should not see the text "[TEST] Test dismissing alert body all pages"
    # New session - the alert should not be visible for logged in user.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body all pages"
    And I should not see the text "[TEST] Test dismissing alert body all pages"

  @api @javascript
  Scenario: Alerts should be dismissed and shown if their content was updated
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test dismissing alert body all pages"
    And I should see the text "[TEST] Test alert body all pages"
    And I press "dismiss-alert-999993"
    And I should not see the text "[TEST] Test dismissing alert body all pages"
    # Revisit same page - the alert should remain dismissed.
    And I visit "civictheme_page" "[TEST] Test alerts on pages"
    And I should not see the text "[TEST] Test dismissing alert body all pages"
    # Update the content.
    When I edit civictheme_alert "[TEST] Test dismissing alert title all pages"
    And wait 3 second
    And I fill in WYSIWYG "field_c_n_body" with "[TEST] Test dismissing alert body all pages updated"
    And wait 3 second
    And I press "Save"
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body all pages"
    And I should see the text "[TEST] Test dismissing alert body all pages updated"

  @api @javascript
  Scenario: Alerts should not be shown if the date range is not today
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "civictheme_page" "[TEST] Test alerts on pages"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body all pages"
    And I should not see the text "[TEST] Test alert body all pages future"

  @api @javascript
  Scenario: Alerts visibility
    Given I am logged in as a user with the "Site Administrator" role
    When I edit civictheme_alert "[TEST] Test alert title visibility"
    And I fill in "Page visibility" with:
      """
      /test-alert/*
      /test-alert-page
      /test-random-alert-page
      """
    And I press "Save"
    When I edit civictheme_page "[TEST] Test alert visibility"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-alert/test-1 |
    And I press "Save"
    When I visit "/test-alert/test-1"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body visibility"
    When I edit civictheme_page "[TEST] Test alert visibility"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-alert/test-2 |
    And I press "Save"
    When I visit "/test-alert/test-2"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body visibility"
    When I edit civictheme_page "[TEST] Test alert visibility"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-alert-page |
    And I press "Save"
    When I visit "/test-alert-page"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body visibility"
    When I edit civictheme_page "[TEST] Test alert visibility"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-alerts |
    And I press "Save"
    When I visit "/test-alerts"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should not see the text "[TEST] Test alert body visibility"
    When I edit civictheme_page "[TEST] Test alert visibility"
    And I uncheck the box "Generate automatic URL alias"
    Then I fill in the following:
      | edit-path-0-alias | /test-random-alert-page |
    And I press "Save"
    When I visit "/test-random-alert-page"
    And wait 5 second
    And I wait for AJAX to finish
    Then I should see the text "[TEST] Test alert body visibility"

  @api @javascript @security
  Scenario: XSS - Alert
    Given civictheme_alert content:
      | nid    | title                                                               | status | field_c_n_alert_type | field_c_n_alert_page_visibility | field_c_n_body                                                                        | field_c_n_date_range:value     | field_c_n_date_range:end_value   |
      | 999976 | <script id="test-alert--title">alert('[TEST] Alert title')</script> | 1      | information          |                                 | <script id="test-alert--field_c_n_body">alert('[TEST] Alert field_c_n_body')</script> | [relative:-1 day#Y-m-d\TH:i:s] | [relative:+10 days#Y-m-d\TH:i:s] |
    Given I am an anonymous user
    When I go to the homepage
    And wait 5 second
    And I wait for AJAX to finish
    Then I should not see an "script#test-alert--title" element
    And I should see the text "alert('[TEST] Alert title')"
    And I should not see an "script#test-alert--field_c_n_body" element
    And I should see the text "alert('[TEST] Alert field_c_n_body')"
