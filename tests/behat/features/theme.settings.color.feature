@civictheme @civictheme_theme_settings
Feature: Check that Color settings are available in theme settings

  @api
  Scenario: The CivicTheme theme color settings.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"

    And I should see an "input[name='colors[use_brand_colors]']" element
    And I should see the text "Brand1"
    And I uncheck the box "Use Brand colors"

    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
