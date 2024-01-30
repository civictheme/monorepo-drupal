@p1 @civictheme @civictheme_quote
Feature: Quote render

  Background:
    Given "civictheme_page" content:
      | title                  | status | moderation_state |
      | [TEST] Page quote test | 1      | published        |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with quote light
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page quote test" has "civictheme_quote" paragraph:
      | field_c_p_content:value    | [TEST] Content 1     |
      | field_c_p_content:format   | civictheme_rich_text |
      | field_c_p_author           | [TEST] Author 1      |
      | field_c_p_theme            | light                |
      | field_c_p_vertical_spacing | both                 |

    When I visit "civictheme_page" "[TEST] Page quote test"
    And I should see an ".ct-quote" element
    And I should see an ".ct-quote.ct-theme-light" element
    And I should not see an ".ct-quote.ct-theme-dark" element
    And I should see an ".ct-quote.ct-vertical-spacing--both" element
    And I should see the text "[TEST] Content 1"
    And I should see the text "[TEST] Author 1"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with quote dark
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page quote test" has "civictheme_quote" paragraph:
      | field_c_p_content:value    | [TEST] Content 2     |
      | field_c_p_content:format   | civictheme_rich_text |
      | field_c_p_author           | [TEST] Author 2      |
      | field_c_p_theme            | dark                 |
      | field_c_p_vertical_spacing | both                 |

    When I visit "civictheme_page" "[TEST] Page quote test"
    And I should see an ".ct-quote" element
    And I should see an ".ct-quote.ct-theme-dark" element
    And I should not see an ".ct-quote.ct-theme-light" element
    And I should see an ".ct-quote.ct-vertical-spacing--both" element
    And I should see the text "[TEST] Content 2"
    And I should see the text "[TEST] Author 2"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with quote without author
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page quote test" has "civictheme_quote" paragraph:
      | field_c_p_content:value    | [TEST] Content 3     |
      | field_c_p_content:format   | civictheme_rich_text |
      | field_c_p_theme            | light                |
      | field_c_p_vertical_spacing | both                 |

    When I visit "civictheme_page" "[TEST] Page quote test"
    And I should see an ".ct-quote" element
    And I should see an ".ct-quote.ct-theme-light" element
    And I should not see an ".ct-quote.ct-theme-dark" element
    And I should see an ".ct-quote.ct-vertical-spacing--both" element
    And I should see the text "[TEST] Content 3"
    And I should not see the text "[TEST] Author 3"
