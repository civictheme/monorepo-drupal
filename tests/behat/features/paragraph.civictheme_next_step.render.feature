@p0 @civictheme @civictheme_next_step
Feature: Next step render

  Background:
    Given "civictheme_page" content:
      | title                        | status |
      | [TEST] Page Next step test   | 1      |
      | [TEST] Page Next step test 1 | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Next step light with vertical spacing
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Next step test" has "civictheme_next_step" paragraph:
      | field_c_p_title            | [TEST] Next step title                          |
      | field_c_p_theme            | light                                           |
      | field_c_p_content:value    | [TEST] Content text                             |
      | field_c_p_content:format   | civictheme_rich_text                            |
      | field_c_p_vertical_spacing | both                                            |
      | field_c_p_link             | 0: [TEST] link 1 - 1: https://example.com/link1 |

    When I visit "civictheme_page" "[TEST] Page Next step test"
    And I should see an ".ct-next-step" element
    And I should see an ".ct-next-step.ct-theme-light" element
    And I should not see an ".ct-next-step.ct-theme-dark" element
    And I should see an ".ct-next-step.ct-vertical-spacing-inset--both" element
    And I should see the text "[TEST] Next step title"
    And I should see the text "[TEST] Content text"
    And I should see an ".ct-next-step__title" element
    And I should see an ".ct-next-step__content" element
    And the response should contain "https://example.com/link1"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with next step dark without vertical space
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Next step test 1" has "civictheme_next_step" paragraph:
      | field_c_p_title            | [TEST] Next step title                          |
      | field_c_p_theme            | dark                                            |
      | field_c_p_content:value    | [TEST] Content text                             |
      | field_c_p_content:format   | civictheme_rich_text                            |
      | field_c_p_vertical_spacing | 0                                               |
      | field_c_p_link             | 0: [TEST] link 2 - 1: https://example.com/link2 |

    When I visit "civictheme_page" "[TEST] Page Next step test 1"
    And I should see an ".ct-next-step" element
    And I should not see an ".ct-next-step.ct-theme-light" element
    And I should see an ".ct-next-step.ct-theme-dark" element
    And I should not see an ".ct-next-step.ct-vertical-spacing-inset--both" element
    And I should see the text "[TEST] Next step title"
    And I should see the text "[TEST] Content text"
    And I should see an ".ct-next-step__title" element
    And I should see an ".ct-next-step__content" element
    And the response should contain "https://example.com/link2"
