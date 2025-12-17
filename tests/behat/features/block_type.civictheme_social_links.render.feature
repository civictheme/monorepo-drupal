@p1 @civitheme @block_type @block_civictheme_social_links

Feature: Social Links Block Render
  Background:
    Given managed file:
      | filename         | uri                                       | path             |
      | test_svg.svg     | public://civictheme_test/test_svg.svg     | test_svg.svg     |
      | test_xss_svg.svg | public://civictheme_test/test_xss_svg.svg | test_xss_svg.svg |

    And "civictheme_icon" media:
      | name                       | field_c_m_icon   |
      | [TEST] CivicTheme Icon     | test_svg.svg     |
      | [TEST] CivicTheme XSS Icon | test_xss_svg.svg |

    Given "civictheme_social_links" block_content:
      | info            | field_b_theme | status | region       |
      | Social Link 1   | light         | 1      | header_top_1 |
      | XSS Social Link | dark          | 1      | header_top_1 |
    And "field_c_b_social_icons" in "civictheme_social_links" "block_content" with "info" of "Social Link 1" has "civictheme_social_icon" paragraph:
      | field_c_p_icon                 | [TEST] CivicTheme Icon                |
      | field_c_p_link                 | 0: Test link - 1: https://example.com/test2 |
    And "field_c_b_social_icons" in "civictheme_social_links" "block_content" with "info" of "XSS Social Link" has "civictheme_social_icon" paragraph:
      | field_c_p_icon                 | [TEST] CivicTheme XSS Icon            |
      | field_c_p_link                 | 0: Test link - 1: https://example.com/test |
    Given I create a block of type "Social Link 1" with:
      | label         | [TEST] Social Link 1 |
      | display_label | 0                    |
      | region        | header_top_1         |
      | status        | 1                    |
    Given I create a block of type "XSS Social Link" with:
      | label         | [TEST] XSS Social Link |
      | display_label | 0                      |
      | region        | header_top_1           |
      | status        | 1                      |
    And the cache has been cleared

  @api @security
  Scenario: CivicTheme social links appear as expected
    Given I am an anonymous user
    When I visit "/"
    # Check embedded parts of SVG.
    Then I should see an "svg.ct-icon g#Download-attachment-panel" element
    Then I should see an "svg.ct-icon g#test-xss-svg" element
    # Ensure embedded script in SVG not rendered.
    And I should not see an "script#test-svg--embedded" element
