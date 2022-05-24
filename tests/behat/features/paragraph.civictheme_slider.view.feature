@civictheme @paragraph @civictheme_slider
Feature: View of Page content with slider component

  Ensure that Page content can be viewed correctly with slider component.

  Background:
    Given "civictheme_image" media:
      | name                      | field_c_m_image        |
      | [TEST] CivicTheme Slide 1 | test_image.jpg         |
      | [TEST] CivicTheme Slide 2 | test_image.jpg         |

    And "civictheme_page" content:
      | title                           | status |
      | [TEST] Page slider test         | 1      |

    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with slider light without background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page slider test" has "civictheme_slider" paragraph:
      | field_c_p_title              | [TEST] slider             |
      | field_c_p_theme              | light                     |
      | field_c_p_space              | both                      |
      | field_c_p_background         | 0                         |
      | field_c_p_link               | 0: [TEST] slider link - 1: https://example.com/sliderlink1 |
      And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] slider" has "civictheme_slider_slide" paragraph:
        | field_c_p_title          | [TEST] slide 1              |
        | field_c_p_image          | [TEST] CivicTheme Slide 1   |
        | field_c_p_content:value  | <h2>[TEST] content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p>  |
        | field_c_p_content:format | civictheme_rich_text        |
        | field_c_p_image_position | left                        |
        | field_c_p_link           | 0: [TEST] link 1 - 1: https://example.com/link1 |
        | field_c_p_topic          | [TEST] Topic 1              |
      And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] slider" has "civictheme_slider_slide" paragraph:
        | field_c_p_title          | [TEST] slide 2              |
        | field_c_p_image          | [TEST] CivicTheme Slide 2   |
        | field_c_p_content:value  | <h2>[TEST] content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p>  |
        | field_c_p_content:format | civictheme_rich_text        |
        | field_c_p_image_position | right                       |
        | field_c_p_link           | 0: [TEST] link 2 - 1: https://example.com/link2  |
        | field_c_p_topic          | [TEST] Topic 2              |

    When I visit "civictheme_page" "[TEST] Page slider test"
    And I wait 5 second
    And I should see the text "[TEST] slider"
    And I should see an "div.civictheme-slider" element
    And I should see an "div.civictheme-slider.civictheme-theme-light" element
    And I should see an "div.civictheme-slider.civictheme-slider--vertical-space-both" element
    And I should not see an "div.civictheme-slider.civictheme-theme-dark" element
    And I should see an "div.civictheme-slider__container" element
    Then I should see the link "[TEST] slider link" with "https://example.com/sliderlink1" in 'div.civictheme-slider__link'
    And I should see an "div.civictheme-slider__slides" element
    Then I should see the text "[TEST] slide 1"
    And I should see the link "[TEST] link 1" with "https://example.com/link1" in 'div.civictheme-slide__links'
    And I should see an "div.civictheme-slider__controls" element
    And I should see the button "Previous"
    And I should see the button "Next"
    And I should see the text "Slide 1 of 2"
    Then I press "Next"
    And wait 2 second
    Then I should see the text "[TEST] slide 2"
    And I should see the link "[TEST] link 2" with "https://example.com/link2"
    And I should see the text "Slide 2 of 2"
    And I should see an "div.civictheme-slider__steps" element
