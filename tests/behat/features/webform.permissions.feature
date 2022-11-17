@civictheme @webform @permissions
Feature: Webform permissions

  @api
  Scenario Outline: Check access for Site Administrator across various Webfrom configurations.
    Given I am logged in as a user with the "civictheme_site_administrator" role
    When I visit "<link>"
    Then I should get a "<response>" HTTP response
    Examples:
      | link                                        | response |
      | /admin/structure/webform                    | 200      |
      | /admin/structure/webform/submissions/manage | 200      |
      | /admin/structure/webform/options/manage     | 200      |
      | /admin/structure/webform/config             | 200      |
      | /admin/structure/webform/help               | 200      |
