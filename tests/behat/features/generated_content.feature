@development
Feature: Generated Content list

  @api
  Scenario: Generated content list exists.
    Given I am an anonymous user
    When I go to "generated-content/components"
    Then the response status code should be 200
    And the response should contain "Generated Page,"
