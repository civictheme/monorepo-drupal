@storybook
Feature: Check that compiled Storybook is available in theme settings

  @api @javascript
  Scenario: The CivicTheme theme settings has compiled storybook loaded
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme"
    Then I see content in iframe with id "storybook"
    And I save 1440 x 3000 screenshot

  @api @javascript
  Scenario: The CivicTheme Demo theme settings has compiled storybook loaded
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"
    Then I see content in iframe with id "storybook"
    And I save 1440 x 3000 screenshot
