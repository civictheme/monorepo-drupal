@civictheme @paragraph @civictheme_quote1
Feature: View of Page content with Quote component

  Ensure that Page content can be viewed correctly with quote component.

  Background:
    Given "civictheme_page" content:
      | title                   | status |
      | [TEST] Page quote test  | 1      |

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with quote light
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page quote test" has "civictheme_quote" paragraph:
      | field_c_p_theme      | light             |
      | field_c_p_space      | both              |
      | field_c_p_content    | [TEST] Content text Pellentesque in ipsum id orci porta dapibus. Donec sollicitudin molestie malesuada. |
      | field_c_p_author     | [TEST] Author     |

    When I visit "civictheme_page" "[TEST] Page quote test"
    And I should see an ".civictheme-quote" element
    And I should see an ".civictheme-quote.civictheme-theme-light" element
    And I should not see an ".civictheme-quote.civictheme-theme-dark" element
    And I should see an ".civictheme-quote.civictheme-quote--vertical-space-both" element

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with quote dark
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page quote test" has "civictheme_quote" paragraph:
      | field_c_p_theme      | dark              |
      | field_c_p_space      | both              |
      | field_c_p_content    | [TEST] Content text Pellentesque in ipsum id orci porta dapibus. Donec sollicitudin molestie malesuada. |
      | field_c_p_author     | [TEST] Author     |

    When I visit "civictheme_page" "[TEST] Page quote test"
    And I should see an ".civictheme-quote" element
    And I should see an ".civictheme-quote.civictheme-theme-dark" element
    And I should not see an ".civictheme-quote.civictheme-theme-light" element
    And I should see an ".civictheme-quote.civictheme-quote--vertical-space-both" element
    And I should see the text "[TEST] Author"
    And I should see an ".civictheme-quote__author" element

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with quote without author
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page quote test" has "civictheme_quote" paragraph:
      | field_c_p_theme      | light             |
      | field_c_p_space      | both              |
      | field_c_p_content    | [TEST] Content text Pellentesque in ipsum id orci porta dapibus. Donec sollicitudin molestie malesuada. |

    When I visit "civictheme_page" "[TEST] Page quote test"
    And I should see an ".civictheme-quote" element
    And I should see an ".civictheme-quote.civictheme-theme-light" element
    And I should not see an ".civictheme-quote.civictheme-theme-dark" element
    And I should see an ".civictheme-quote.civictheme-quote--vertical-space-both" element
    And I should not see an ".civictheme-quote__author" element
