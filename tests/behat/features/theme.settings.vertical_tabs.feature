@p0 @civictheme @civictheme_theme_settings @civictheme_vertical_tabs_settings
Feature: Correct vertical tab does not focus on form validation

  @api @javascript
  Scenario: Validate vertical tab with an error is not selected on theme settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit current theme settings page

    # Reset settings.
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

    And I scroll to an element with id "edit-components-footer"
    Then I click on ".vertical-tabs__menu-item a[href='#edit-components-footer']" element
    And I fill in "Footer background image path" with "dummy-text"
    Then I click on ".vertical-tabs__menu-item a[href='#edit-components-skip-link']" element
    And I press "Save configuration"

    And I scroll to an element with id "edit-components-footer"
    And I should see an ".vertical-tabs__menu-item.is-selected a[href='#edit-components-footer']" element
    Then I should see the text "The file at provided path does not exist."
