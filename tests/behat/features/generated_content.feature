@p0 @development
Feature: Generated Content list

  @api
  Scenario: Generated content list exists.
    Given I am an anonymous user

    When I go to "generated-content/pages"
    Then the response status code should be 200
    And the response should contain "Generated Pages"

    When I go to "generated-content/events"
    Then the response status code should be 200
    And the response should contain "Generated Events"

    When I go to "generated-content"
    Then I should be in the "generated-content/components" path
