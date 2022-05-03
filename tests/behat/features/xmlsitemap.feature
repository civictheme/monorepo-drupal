@civictheme
Feature: XML Sitemap

  Ensure that the XML Sitemap exists.

  @api @wip5
  Scenario: XML Sitemap is accessible

    Given civictheme_page content:
      | title                 | status | path                 |
      | [TEST] Published page | 1      | /test-published-page |
      | [TEST] Draft page     | 0      | /test-draft-page     |
    And civictheme_event content:
      | title                  | status | path                         |
      | [TEST] Published event | 1      | /events/test-published-event |
      | [TEST] Draft event     | 0      | /events/test-draft-event     |
    And civictheme_alert content:
      | title                  | status | path                         |
      | [TEST] Published alert | 1      | /alerts/test-published-alert |
      | [TEST] Draft alert     | 0      | /alerts/test-draft-alert     |

    Given I run drush "simple-sitemap:generate --uri=http://civictheme.docker.amazee.io"

    When I go to "sitemap.xml"
    Then the response status code should be 200
    And the response should contain "http://civictheme.docker.amazee.io/"

    And the response should contain "http://civictheme.docker.amazee.io/test-published-page"
    And the response should not contain "http://civictheme.docker.amazee.io/test-draft-page"

    And the response should contain "http://civictheme.docker.amazee.io/events/test-published-event"
    And the response should not contain "http://civictheme.docker.amazee.io/events/test-draft-event"

    And the response should not contain "http://civictheme.docker.amazee.io/alerts/test-published-alert"
    And the response should not contain "http://civictheme.docker.amazee.io/alerts/test-draft-alert"
