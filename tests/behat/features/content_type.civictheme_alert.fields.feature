@p0 @civictheme @content_type @civictheme_alert
Feature: CivicTheme Alert content type fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_alert"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "[name='title[0][value]']" element
    And should see an "[name='title[0][value]'].required" element
    And should not see an "[name='title[0][value]'][disabled]" element

    And I see field "Message"
    And should see an "[name='field_c_n_body[0][value]']" element
    And should see an "[name='field_c_n_body[0][value]'].required" element
    And should not see an "[name='field_c_n_body[0][value]'][disabled]" element

    And I see field "Type"
    And should see a "[name='field_c_n_alert_type']" element
    And should see a "[name='field_c_n_alert_type'].required" element
    And should not see a "[name='field_c_n_alert_type'][disabled]" element

    And I should see the text "Start date"
    And should see a "[name='field_c_n_date_range[0][value][date]']" element
    And should see a "[name='field_c_n_date_range[0][value][date]'].required" element
    And should not see a "[name='field_c_n_date_range[0][value][date]'][disabled]" element
    And I should see the text "End date"
    And should see a "[name='field_c_n_date_range[0][end_value][date]']" element
    And should see a "[name='field_c_n_date_range[0][end_value][date]'].required" element
    And should not see a "[name='field_c_n_date_range[0][end_value][date]'][disabled]" element

    And I see field "Page visibility"
    And should see a "[name='field_c_n_alert_page_visibility[0][value]']" element
    And should not see a "[name='field_c_n_alert_page_visibility[0][value]'].required" element
    And should not see a "[name='field_c_n_alert_page_visibility[0][value]'][disabled]" element
