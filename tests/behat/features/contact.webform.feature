@civic @webform @form_element
Feature: Tests the Civic form integration with webform

  @api
    Scenario: Form inputs are correctly validated

    Given I am an anonymous user
    When I visit "/form/contact"
    Then I press the "Send message" button
    And I should see "Your Name field is required."
    And I should see "Your Email field is required."
    And I should see "Message field is required"
    And I should see "Subject field is required"
    And I fill in "Your Name" with "[TEST] Name"
    And I fill in "subject" with "[TEST] Subject"
    And I fill in "edit-message" with "[TEST] Message"
    And I fill in "email" with "test"
    And I press the "Send message" button
    And I should see "The email address test is not valid."

  @api
    Scenario: Contact webform can be filled in and submitted correctly and form label, selectors, id are present
    Given I am an anonymous user
    When I visit "/form/contact"
    Then I fill in "Your Name" with "[TEST] Name"
    And I fill in "email" with "test@email.com"
    And I fill in "subject" with "[TEST] Subject"
    And I fill in "edit-message" with "[TEST] Message"
    And I press the "Send message" button
    And I should see "Your message has been sent."
