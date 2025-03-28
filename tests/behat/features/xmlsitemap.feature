@p0 @civictheme @xmlsitemap
Feature: XML Sitemap

  @api
  Scenario: XML Sitemap is accessible

    Given civictheme_page content:
      | title                 | status | moderation_state | path                 |
      | [TEST] Published page | 1      | published        | /test-published-page |
      | [TEST] Draft page     | 0      | draft            | /test-draft-page     |
    And civictheme_event content:
      | title                  | status | moderation_state | path                         |
      | [TEST] Published event | 1      | published        | /events/test-published-event |
      | [TEST] Draft event     | 0      | draft            | /events/test-draft-event     |
    And civictheme_alert content:
      | title                  | status | moderation_state | path                         |
      | [TEST] Published alert | 1      | published        | /alerts/test-published-alert |
      | [TEST] Draft alert     | 0      | draft            | /alerts/test-draft-alert     |

    Given I run drush "simple-sitemap:generate" "--uri=http://example.com"
    When I go to "sitemap.xml"
    Then the response status code should be 200

    And the response should contain "http://example.com/test-published-page"
    And the response should not contain "http://example.com/test-draft-page"

    And the response should contain "http://example.com/events/test-published-event"
    And the response should not contain "http://example.com/events/test-draft-event"

    And the response should not contain "http://example.com/alerts/test-published-alert"
    And the response should not contain "http://example.com/alerts/test-draft-alert"

    # Assert that views' paths are present.
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-no-filter"
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-one-filter-single-select"
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-one-filter-single-select-exposed-block"
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-one-filter-multi-select"
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-one-filter-multi-select-exposed-block"
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-multiple-filters"
    And the response should contain "http://example.com/civictheme-no-sidebar/listing-multiple-filters-exposed-block"
