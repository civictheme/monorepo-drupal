@civictheme @civictheme_test
Feature: Tests the Civictheme Views implementation

  Ensure that Exposed form of Views is not broken with CivicTheme.

  Background:
    Given civictheme_page content:
      | title                         | status | moderation_state |
      | [TEST] Test CivicTheme Page 1 | 1      | published        |
      | [TEST] Test CivicTheme Page 2 | 1      | published        |

  @api
  Scenario: Ensure that View filters are not broken with CivicTheme.
    When I go to "/test/page_1"
    Then I should not see the text "With exposed block filters"
    And I should not see an ".views-exposed-form .civictheme-form-element--select.civictheme-form-element--status" element
    And I should not see an ".civictheme-form-element--select.civictheme-form-element--status" element
    And I should not see an ".views-exposed-form .civictheme-form-element--textfield.civictheme-form-element--title" element
    And I should not see an ".civictheme-form-element--textfield.civictheme-form-element--title" element
    When I go to "/test/page_2"
    Then I should not see the text "With exposed block filters"
    And I should not see an ".views-exposed-form.block-views .civictheme-form-element--select.civictheme-form-element--status" element
    And I should see an ".civictheme-form-element--select.civictheme-form-element--status" element
    And I should not see an ".views-exposed-form.block-views .civictheme-form-element--textfield.civictheme-form-element--title" element
    And I should see an ".civictheme-form-element--textfield.civictheme-form-element--title" element
    When I go to "/test/page_3"
    Then I should see the text "With exposed block filters"
    And I should see an ".views-exposed-form.block-views .civictheme-form-element--select.civictheme-form-element--status" element
    And I should see an ".views-exposed-form.block-views .civictheme-form-element--textfield.civictheme-form-element--title" element
