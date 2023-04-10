@p0 @civictheme @civictheme_theme_settings @civictheme_theme_optout
Feature: Opt-out form is available in settings.

  @api
  Scenario: Opt-out flags can be set
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I fill in "Opt-out flags" with:
    """
    test.flag1
    test.flag2
    test.flag3
    """
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And the response should contain "test.flag1"
    And the response should contain "test.flag2"
    And the response should contain "test.flag3"
