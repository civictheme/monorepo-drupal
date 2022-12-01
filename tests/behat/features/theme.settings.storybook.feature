@p0 @civictheme @civictheme_theme_settings
Feature: Compiled Storybook is available in the theme settings

  @api @javascript
  Scenario: The CivicTheme theme settings has compiled storybook loaded
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme"
    Then I see content in iframe with id "storybook"
    And I click an "#edit-storybook" element
    And I save 1440 x 3000 screenshot
    And I should see the text "themes/contrib/civictheme/storybook-static/index.html?cachebust="

  @api @javascript @subtheme
  Scenario: The CivicTheme Demo theme settings has compiled storybook loaded
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"
    Then I see content in iframe with id "storybook"
    And I click an "#edit-storybook" element
    And I save 1440 x 3000 screenshot
    And I should see the text "themes/custom/civictheme_demo/storybook-static/index.html?cachebust="
