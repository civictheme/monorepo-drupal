@p0 @civictheme @civictheme_theme_settings @civictheme_theme_reset
Feature: Theme settings can be reset to defaults.

  @api
  Scenario: Theme settings can be reset to defaults
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I uncheck the box "Use Color Selector"
    And I uncheck the box "Use Brand colors"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And the "Use Color Selector" checkbox should not be checked
    And the "Use Brand colors" checkbox should not be checked

    When I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."
    And the "Use Color Selector" checkbox should be checked
    And the "Use Brand colors" checkbox should be checked

  @api
  Scenario: Theme settings can not be reset to defaults if safety check is not set
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I uncheck the box "Use Color Selector"
    And I uncheck the box "Use Brand colors"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And the "Use Color Selector" checkbox should not be checked
    And the "Use Brand colors" checkbox should not be checked

    When I press "reset_to_defaults"
    Then I should not see the text "Theme configuration was reset to defaults."
    And I should see the text "Please check the box to confirm theme settings reset."
    And the "Use Color Selector" checkbox should not be checked
    And the "Use Brand colors" checkbox should not be checked
