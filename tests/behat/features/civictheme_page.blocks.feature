@civictheme @blocks
Feature: Tests the CivicTheme demo block

  Ensure that CivicTheme Demo block are visible.

  @api
  Scenario: Anonymous user visits homepage
    Given I go to the homepage
    And I should be in the "<front>" path
    And I should see the text "We acknowledge the traditional owners of the country throughout Australia and their continuing connection to land, sea and community. We pay our respect to them and their cultures and to the elders past and present."
    And I should see the text "Commonwealth of Australia"
    And I should see the text "Sign up for news and updates from our agency."
    And I should see 3 "div.civictheme-social-links__item a" elements
    And I should see an "div.civictheme-banner__wrapper__inner" element
    Then I save screenshot
