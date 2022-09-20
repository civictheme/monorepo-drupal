@civictheme @paragraph @civictheme_next_step
Feature: View of Page content with Next steps component

  Ensure that Page content can be viewed correctly with Next steps component.

  Background:
    Given "civictheme_page" content:
      | title                         | status |
      | [TEST] Page Next steps test   | 1      |
      | [TEST] Page Next steps test 1 | 1      |

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with Next steps light with vertical spacing
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Next steps test" has "civictheme_next_step" paragraph:
      | field_c_p_title            | [TEST] Next steps title                         |
      | field_c_p_theme            | light                                           |
      | field_c_p_summary          | Summary text                                    |
      | field_c_p_vertical_spacing | both                                            |
      | field_c_p_link             | 0: [TEST] link 1 - 1: https://example.com/link1 |

    When I visit "civictheme_page" "[TEST] Page Next steps test"
    And I scroll to an element with id "main-content"
    And I should see an "a.ct-next-steps" element
    And I should see an "a.ct-next-steps.ct-theme-light" element
    And I should not see an "a.ct-next-steps.ct-theme-dark" element
    And I should see an "a.ct-next-steps.ct-vertical-spacing-inset--both" element
    And I should see an "div.ct-next-steps__content" element
    And I should see an "div.ct-next-steps__text" element
    And I should see the text "[TEST] Next steps title"
    And I should see an "div.ct-next-steps__title" element
    And I should see an "div.ct-next-steps__summary" element
    And I should see an "div.ct-next-steps__icon.ct-next-steps__read-more" element

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with next_step dark without space
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Next steps test 1" has "civictheme_next_step" paragraph:
      | field_c_p_title            | [TEST] Next steps title                         |
      | field_c_p_theme            | dark                                            |
      | field_c_p_summary          | Summary text                                    |
      | field_c_p_vertical_spacing | 0                                               |
      | field_c_p_link             | 0: [TEST] link 1 - 1: https://example.com/link1 |

    When I visit "civictheme_page" "[TEST] Page Next steps test 1"
    And I scroll to an element with id "main-content"
    And I should see an "a.ct-next-steps" element
    And I should not see an "a.ct-next-steps.ct-theme-light" element
    And I should see an "a.ct-next-steps.ct-theme-dark" element
    And I should not see an "a.ct-next-steps.ct-vertical-spacing-inset--both" element
    And I should see an "div.ct-next-steps__content" element
    And I should see an "div.ct-next-steps__text" element
    And I should see the text "[TEST] Next steps title"
    And I should see an "div.ct-next-steps__title" element
    And I should see an "div.ct-next-steps__summary" element
    And I should see an "div.ct-next-steps__icon.ct-next-steps__read-more" element
