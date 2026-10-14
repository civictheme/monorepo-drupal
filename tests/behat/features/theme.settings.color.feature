@p0 @civictheme @civictheme_theme_settings @civictheme_theme_settings_color
Feature: Colour settings are available in the theme settings

  The 2.x colour selector is the UI Kit colour picker embedded in the theme
  settings form: it edits a recipe (brand inputs plus token overrides), emits
  the changed CSS custom properties in the browser, and both are stored in
  theme settings. The overrides stylesheet is attached after the token
  variables stylesheet when the Colour Picker is enabled.

  @api
  Scenario: Colour picker fields are present.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    # Reset settings.
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

    When I visit current theme settings page
    Then I should see an "input[name='colors[use_color_selector]']" element
    And I should see an "iframe[data-civictheme-colour-picker]" element
    And I should see a "textarea[name='colors[recipe]']" element
    And I should see a "textarea[name='colors[css]']" element

    When I press "Save configuration"
    Then I should see the text "The configuration options have been saved."

  @api
  Scenario: Invalid recipe JSON is rejected.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I fill in "colors[recipe]" with "not json"
    And I press "Save configuration"
    Then I should see the text "The colour recipe must be valid recipe JSON"

  @api
  Scenario: The colour overrides stylesheet is attached when the Colour Picker is used.
    Given I set the current theme setting "colors.css" to ":root{--ct-color-highlight:#ffcc00}"
    And I set the current theme setting "colors.use_color_selector" to "0"
    And I run drush 'civictheme:clear-cache' '--include=themes/contrib/civictheme/src/Drush'

    When I go to the homepage
    Then the response should not contain "/sites/default/files/css-variables"

    When I set the current theme setting "colors.use_color_selector" to "1"
    And I run drush 'civictheme:clear-cache' '--include=themes/contrib/civictheme/src/Drush'

    And I go to the homepage
    Then the response should contain "/sites/default/files/css-variables"

    When I set the current theme setting "colors.use_color_selector" to "0"
    And I set the current theme setting "colors.css" to ""
    And I run drush 'civictheme:clear-cache' '--include=themes/contrib/civictheme/src/Drush'

  @api
  Scenario: The colour overrides stylesheet can be generated through a Drush command.
    Given I set the current theme setting "colors.css" to ":root{--ct-color-highlight:#ffcc00}"
    And I run drush 'civictheme:stylesheet test' '--include=themes/contrib/civictheme/src/Drush'

    When I go to "/sites/default/files/css-variables.test.css"
    Then the response should contain "--ct-color-highlight:#ffcc00"

    When I set the current theme setting "colors.css" to ""
    And I run drush 'civictheme:clear-cache' '--include=themes/contrib/civictheme/src/Drush'
