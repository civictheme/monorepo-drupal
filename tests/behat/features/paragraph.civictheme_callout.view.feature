@civictheme @paragraph @civictheme_callout
Feature: View of Page content with Callout component

  Ensure that Page content can be viewed correctly with callout component.

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page callout test | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with callout light
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page callout test" has "civictheme_callout" paragraph:
      | field_c_p_title   | [TEST] callout title                                                                               |
      | field_c_p_theme   | light                                                                                              |
      | field_c_p_summary | Summary text                                                                                       |
      | field_c_p_links   | 0: [TEST] link 1 - 1: https://example.com/link1, 0: [TEST] link 11 - 1: https://example.com/link11 |

    When I visit "civictheme_page" "[TEST] Page callout test"
    And I should see an "div.ct-callout" element
    And I should see an "div.ct-callout.ct-theme-light" element
    And I should not see an "div.ct-callout.ct-theme-dark" element
    And I should see an "div.ct-callout--wrapper" element
    And I should see an "div.ct-callout__text" element
    And I should see an "div.ct-callout__title" element
    And I should see an "div.ct-callout__summary" element
    And I should see an "div.ct-callout__links" element
    And I should see the text "[TEST] callout title"
    Then I should see the link "[TEST] link 1" with "https://example.com/link1" in 'div.ct-callout__links'
    Then I should see the link "[TEST] link 11" with "https://example.com/link11" in 'div.ct-callout__links'

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with callout dark
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page callout test" has "civictheme_callout" paragraph:
      | field_c_p_title   | [TEST] callout title                                                                               |
      | field_c_p_theme   | dark                                                                                               |
      | field_c_p_summary | Summary text                                                                                       |
      | field_c_p_links   | 0: [TEST] link 1 - 1: https://example.com/link1, 0: [TEST] link 11 - 1: https://example.com/link11 |

    When I visit "civictheme_page" "[TEST] Page callout test"
    And I should see an "div.ct-callout" element
    And I should see an "div.ct-callout.ct-theme-dark" element
    And I should not see an "div.ct-callout.ct-theme-light" element
    And I should see an "div.ct-callout--wrapper" element
    And I should see an "div.ct-callout__text" element
    And I should see an "div.ct-callout__title" element
    And I should see an "div.ct-callout__summary" element
    And I should see an "div.ct-callout__links" element
    And I should see the text "[TEST] callout title"
    Then I should see the link "[TEST] link 1" with "https://example.com/link1" in 'div.ct-callout__links'
    Then I should see the link "[TEST] link 11" with "https://example.com/link11" in 'div.ct-callout__links'
