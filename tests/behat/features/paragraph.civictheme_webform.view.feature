@civictheme @paragraph @civictheme_webform
Feature: View of Page content with webform component

  Ensure that Page content can be viewed correctly with Webform component.

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page Webform test | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Webform
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Webform test" has "civictheme_webform" paragraph:
      | field_c_p_webform    | Contact |
      | field_c_p_theme      | light   |
      | field_c_p_vertical_spacing      | both    |
      | field_c_p_background | 0       |

    When I visit "civictheme_page" "[TEST] Page Webform test"
    And I should see an "div.ct-webform" element
    And I should see an "div.ct-webform.ct-theme-light" element
    And I should see an "div.ct-webform.ct-webform--vertical-space-both" element
    And I should not see an "div.ct-webform.ct-theme-dark" element
