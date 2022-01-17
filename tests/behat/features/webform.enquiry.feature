@civic @webform @form_element
Feature: Tests the Civic form integration with webform

  @api
  Scenario: Form inputs are correctly validated
    Given I am an anonymous user
    When I visit "/form/civic-enquiry"
    Then I press the "Send" button
    And I should see "First name field is required."
    And I should see "Last name field is required."
    And I should see "Phone number field is required."
    And I should see "Email field is required."
    And I should see "Contact me by field is required"
    And I fill in "First name" with "[TEST] Name"
    And I fill in "Last name" with "[TEST] Name"
    And I fill in "edit-enquiry" with "[TEST] Message"
    And I fill in "Phone number" with "111111111"
    And I fill in "email" with "test"
    And I press the "Send" button
    And I should see "The email address test is not valid."

  @api
  Scenario: Contact webform can be filled in and submitted correctly and form label, selectors, id are present
    Given I am an anonymous user
    When I visit "/form/civic-enquiry"
    Then I fill in "First name" with "[TEST] Name"
    Then I fill in "Last name" with "[TEST] Name"
    And I fill in "email" with "test@email.com"
    And I fill in "Phone number" with "111111111"
    And I fill in "edit-enquiry" with "[TEST] Message"
    And I select the radio button "Telephone"
    And I check the box "edit-subscribe-to-list-all"
    And I press the "Send" button
    And I should see "New submission added to Enquiry."
