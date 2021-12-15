@civic
Feature: XML Sitemap

  Ensure that the XML Sitemap exists.

  @api
  Scenario: XML Sitemap is accessible

    Given civic_page content:
      | title                 | status | path                 |
      | [TEST] Published page | 1      | /test-published-page |
      | [TEST] Draft page     | 0      | /test-draft-page     |
    And civic_event content:
      | title                  | status | path                  |
      | [TEST] Published event | 1      | /test-published-event |
      | [TEST] Draft event     | 0      | /test-draft-event     |
    And civic_project content:
      | title                    | status | path                    |
      | [TEST] Published project | 1      | /test-published-project |
      | [TEST] Draft project     | 0      | /test-draft-project     |
    And civic_alert content:
      | title                  | status | path                  |
      | [TEST] Published alert | 1      | /test-published-alert |
      | [TEST] Draft alert     | 0      | /test-draft-alert     |

    Given I run drush "simple-sitemap:generate --uri=http://civictheme.docker.amazee.io"

    When I go to "sitemap.xml"
    Then the response status code should be 200
    And the response should contain "http://civictheme.docker.amazee.io/"

    And the response should contain "http://civictheme.docker.amazee.io/test-published-page"
    And the response should not contain "http://civictheme.docker.amazee.io/test-draft-page"

    And the response should contain "http://civictheme.docker.amazee.io/test-published-event"
    And the response should not contain "http://civictheme.docker.amazee.io/test-draft-event"

    And the response should contain "http://civictheme.docker.amazee.io/test-published-project"
    And the response should not contain "http://civictheme.docker.amazee.io/test-draft-project"

    And the response should not contain "http://civictheme.docker.amazee.io/test-published-alert"
    And the response should not contain "http://civictheme.docker.amazee.io/test-draft-project"
