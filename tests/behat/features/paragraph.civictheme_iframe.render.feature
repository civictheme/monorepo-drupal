@p0 @civictheme @civictheme_iframe
Feature: IFrame render

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page iframe test  | 1      |
      | [TEST] Page iframe test2 | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with iframe light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page iframe test" has "civictheme_iframe" paragraph:
      | field_c_p_theme            | light             |
      | field_c_p_vertical_spacing | both              |
      | field_c_p_height           | 600               |
      | field_c_p_width            | 400               |
      | field_c_p_attributes       | attr1=val1        |
      | field_c_p_background       | 0                 |
      | field_c_p_url              | http://nginx:8080 |

    When I visit "civictheme_page" "[TEST] Page iframe test"
    And I should see an "iframe.ct-iframe" element
    And I should see an "iframe.ct-iframe.ct-theme-light" element
    And I should see an "iframe.ct-iframe.ct-vertical-spacing-inset--both" element
    And I should see an "iframe[height=600]" element
    And I should see an "iframe[width=400]" element
    And I should see an "iframe[attr1=val1]" element
    And I should see an "iframe[src^=http]" element

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with iframe dark with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page iframe test2" has "civictheme_iframe" paragraph:
      | field_c_p_theme            | dark              |
      | field_c_p_vertical_spacing | both              |
      | field_c_p_height           | 600               |
      | field_c_p_width            | 400               |
      | field_c_p_attributes       | attr1=val1        |
      | field_c_p_background       | 1                 |
      | field_c_p_url              | http://nginx:8080 |

    When I visit "civictheme_page" "[TEST] Page iframe test2"
    And I should see an "iframe.ct-iframe" element
    And I should see an "iframe.ct-iframe.ct-theme-dark" element
    And I should see an "iframe.ct-iframe.ct-vertical-spacing-inset--both" element
    And I should see an "iframe.ct-iframe--with-background" element
    And I should see an "iframe[height=600]" element
    And I should see an "iframe[width=400]" element
    And I should see an "iframe[attr1=val1]" element
    And I should see an "iframe[src^=http]" element
