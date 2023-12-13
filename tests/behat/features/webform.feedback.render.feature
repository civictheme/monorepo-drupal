@p0 @civictheme @webform @test
Feature: Webform render

  @api @javascript
  Scenario: Form inputs are correctly validated
    Given I am an anonymous user
    When I visit "/form/civictheme-feedback"
    And I should see 3 ".progress-step" elements
    And I should see the text "Personal information"
    And I should see the text "Additional information"
    And I should see an "[name='name']" element
    And I should see an "[name='email']" element
    And I should see an "[name='message']" element
    And I fill in "Your Name" with "[TEST] Name"
    And I fill in "Your Email" with "test@email.com"
    And I fill in "edit-message" with "[TEST] Message"
    And I should see the button "Next"
    And I should not see the "Previous" button
    And I press the "Next" button
    And I should not see an "[name='name']" element
    And I should not see an "[name='email']" element
    And I should not see an "[name='message']" element
    And I should see an "[name='reason']" element
    And I should see the text "Reason for Contacting"
    And I should see the text "Subscribe to Newsletter"
    And I should see the text "Preferred Contact Method"
    And I should not see the "Next" button
    And I should see the button "Previous"
    Then I select "support" from "reason"
    And I should see an "[name='support_ticket_number']" element
    And I should not see a visible "[name='how_did_you_hear']" element
    Then I select "general" from "reason"
    And I should not see a visible "[name='support_ticket_number']" element
    And I should see an "[name='how_did_you_hear']" element
    And I should see the button "Submit"

