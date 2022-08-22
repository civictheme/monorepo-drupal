@civictheme @civictheme_page @civictheme_content
Feature: View of Page content

  Ensure that Page content can be viewed correctly with contnet component.

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page content test | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with content light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content test" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                                                                                                                                                    |
      | field_c_p_content:value  | <h2>[TEST] Page content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                     |
      | field_c_p_background     | 0                                                                                                                                                        |

    When I visit "civictheme_page" "[TEST] Page content test"
    And I should see an "div.civictheme-basic-content" element
    And I should not see an "div.civictheme-basic-content.civictheme-content--with-background" element
    And I should see an "div.civictheme-basic-content.civictheme-theme-light" element
    And I should see the text "[TEST] Page content"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with content dark with background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content test" has "civictheme_content" paragraph:
      | field_c_p_theme          | dark                                                                                                                                                     |
      | field_c_p_content:value  | <h2>[TEST] Page content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                     |
      | field_c_p_background     | 1                                                                                                                                                        |

    When I visit "civictheme_page" "[TEST] Page content test"
    And I should see an "div.civictheme-basic-content" element
    And I should see an "div.civictheme-basic-content.civictheme-content--with-background" element
    And I should see an "div.civictheme-basic-content.civictheme-theme-dark" element
    And I should not see an "div.civictheme-basic-content.civictheme-theme-light" element
    And I should see the text "[TEST] Page content"
