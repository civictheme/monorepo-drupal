@storybook
Feature: Check that compiled Storybook is available in theme settings

  @api @javascript
  Scenario: The Civic theme settings has compiled storybook loaded
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civic"
    Then I see content in iframe with id "storybook"
    And I save 1440 x 3000 screenshot

  @api @javascript
  Scenario: The Civic Demo theme settings has compiled storybook loaded
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civic_demo"
    Then I see content in iframe with id "storybook"
    And I save 1440 x 3000 screenshot
