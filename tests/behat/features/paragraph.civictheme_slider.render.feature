@p0 @civictheme @civictheme_slider @test1
Feature: Slider render

  Background:
    Given "civictheme_image" media:
      | name                 | field_c_m_image |
      | [TEST] Image Slide 1 | test_image.jpg  |
      | [TEST] Image Slide 2 | test_image.jpg  |

    And "civictheme_topics" terms:
      | name            |
      | [TEST] Topic 11 |
      | [TEST] Topic 12 |
      | [TEST] Topic 21 |
      | [TEST] Topic 22 |

    And "civictheme_page" content:
      | title                   | status | changed    | field_c_n_summary      | field_c_n_topics                 | field_c_n_thumbnail  |
      | [TEST] Page slider test | 1      |            |                        |                                  |                      |
      | [TEST] Page slider ref  | 1      |            |                        |                                  |                      |
      | [TEST] Event slider ref | 1      |            |                        |                                  |                      |
      | [TEST] Page slider 1    | 1      | 2021-05-29 | [TEST] Summary slide 1 | [TEST] Topic 11, [TEST] Topic 12 | [TEST] Image Slide 1 |
      | [TEST] Page slider 2    | 1      | 2021-05-30 | [TEST] Summary slide 2 | [TEST] Topic 21, [TEST] Topic 22 | [TEST] Image Slide 2 |

    And "civictheme_event" content:
      | title              | status | changed                | field_c_n_summary      | field_c_n_thumbnail  | field_c_n_topics                 | field_c_n_date_range:value | field_c_n_date_range:end_value |
      | [TEST] Event 1 ref | 1      | [relative:-16 minutes] | [TEST] Summary slide 1 | [TEST] Image Slide 1 | [TEST] Topic 11, [TEST] Topic 12 | 2022-07-01T09:45:00        | 2022-08-14T11:30:00            |


  @api @javascript
  Scenario: Slider, Slide light, background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page slider test" has "civictheme_slider" paragraph:
      | field_c_p_title            | [TEST] Slider |
      | field_c_p_theme            | light         |
      | field_c_p_vertical_spacing | both          |
      | field_c_p_background       | 0             |
    And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] Slider" has "civictheme_slider_slide" paragraph:
      | field_c_p_title          | [TEST] Slide 1 title                                                                                                                                        |
      | field_c_p_image          | [TEST] Image Slide 1                                                                                                                                        |
      | field_c_p_date           | 2021-04-29                                                                                                                                                  |
      | field_c_p_content:value  | <h2>[TEST] Slide 1 content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                        |
      | field_c_p_image_position | left                                                                                                                                                        |
      | field_c_p_links          | 0: [TEST] link 11 - 1: https://example.com/link11, 0: [TEST] link 12 - 1: https://example.com/link12                                                        |
      | field_c_p_topics         | [TEST] Topic 11, [TEST] Topic 12                                                                                                                            |
    And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] Slider" has "civictheme_slider_slide" paragraph:
      | field_c_p_title          | [TEST] Slide 2 title                                                                                                                                        |
      | field_c_p_image          | [TEST] Image Slide 2                                                                                                                                        |
      | field_c_p_date           | 2021-04-30                                                                                                                                                  |
      | field_c_p_content:value  | <h2>[TEST] Slide 2 content</h2> <p>Praesent sapien massa, convallis a pellentesque nec, egestas non nisi. Curabitur arcu erat, accumsan id imperdiet et</p> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                        |
      | field_c_p_image_position | right                                                                                                                                                       |
      | field_c_p_links          | 0: [TEST] link 21 - 1: https://example.com/link21, 0: [TEST] link 22 - 1: https://example.com/link22                                                        |
      | field_c_p_topics         | [TEST] Topic 21, [TEST] Topic 22                                                                                                                            |

    When I visit "civictheme_page" "[TEST] Page slider test"
    And I wait 5 second

    Then I should see the text "[TEST] Slider"
    And I should see an ".ct-slider" element
    And I should see an ".ct-slider.ct-theme-light" element
    And I should not see an ".ct-slider.ct-theme-dark" element
    And I should see an ".ct-slider.ct-vertical-spacing-inset--both" element

    And I should see the text "[TEST] Slide 1 title"
    And I should see the text "29 Apr 2021"
    And I should see the text "[TEST] Slide 1 content"
    And I should see the link "[TEST] link 11" with "https://example.com/link11"
    And I should see the link "[TEST] link 12" with "https://example.com/link12"
    And I should see the text "[TEST] Topic 11"
    And I should see the text "[TEST] Topic 12"
    And I should see the button "Previous"
    And I should see the button "Next"
    And I should see the text "Slide 1 of 2"

    When I press "Next"
    And wait 2 second

    Then I should see the text "[TEST] Slide 2 title"
    And I should see the text "30 Apr 2021"
    And I should see the text "[TEST] Slide 2 content"
    And I should see the link "[TEST] link 21" with "https://example.com/link21"
    And I should see the link "[TEST] link 22" with "https://example.com/link22"
    And I should see the text "[TEST] Topic 21"
    And I should see the text "[TEST] Topic 22"
    And I should see the button "Previous"
    And I should see the button "Next"
    And I should see the text "Slide 2 of 2"

  @api @javascript
  Scenario: Slider, Reference Page slide light, background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page slider ref" has "civictheme_slider" paragraph:
      | field_c_p_title            | [TEST] Slider ref |
      | field_c_p_theme            | light             |
      | field_c_p_vertical_spacing | both              |
      | field_c_p_background       | 0                 |
    And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] Slider ref" has "civictheme_slider_slide_ref" paragraph:
      | field_c_p_theme          | light                |
      | field_c_p_reference      | [TEST] Page slider 1 |
      | field_c_p_image_position | right                |
    And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] Slider ref" has "civictheme_slider_slide_ref" paragraph:
      | field_c_p_theme          | light                |
      | field_c_p_reference      | [TEST] Page slider 2 |
      | field_c_p_link_text      | [TEST] Link          |
      | field_c_p_image_position | right                |

    When I visit "civictheme_page" "[TEST] Page slider ref"
    And I wait 5 second

    Then I should see the text "[TEST] Page slider 1"
    And I should see the text "29 May 2021"
    And I should see the text "[TEST] Summary slide 1"
    And I should see the text "[TEST] Topic 11"
    And I should see the text "[TEST] Topic 12"

    And I should see the button "Previous"
    And I should see the button "Next"
    And I should see the text "Slide 1 of 2"

    When I press "Next"
    And wait 2 second

    Then I should see the text "[TEST] Page slider 2"
    And I should see the text "30 May 2021"
    And I should see the text "[TEST] Summary slide 2"
    And I should see the text "[TEST] Topic 21"
    And I should see the text "[TEST] Topic 22"
    And I should see the text "[TEST] Link"
    And I should see the text "Slide 2 of 2"

  @api @javascript
  Scenario: Slider, Reference Event slide light, background
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Event slider ref" has "civictheme_slider" paragraph:
      | field_c_p_title            | [TEST] Slider ref |
      | field_c_p_theme            | light             |
      | field_c_p_vertical_spacing | both              |
      | field_c_p_background       | 0                 |
    And "field_c_p_slides" in "civictheme_slider" "paragraph" with "field_c_p_title" of "[TEST] Slider ref" has "civictheme_slider_slide_ref" paragraph:
      | field_c_p_theme          | light              |
      | field_c_p_reference      | [TEST] Event 1 ref |
      | field_c_p_image_position | right              |

    When I visit "civictheme_page" "[TEST] Event slider ref"
    And I wait 5 second

    Then I should see the text "[TEST] Event 1 ref"
    And I should see the text "1 Jul 2022"
    And I should see the text "14 Aug 2022"
    And I should see the text "[TEST] Summary slide 1"
    And I should see the text "[TEST] Topic 11"
    And I should see the text "[TEST] Topic 12"

    And I should see the button "Previous"
    And I should see the button "Next"
    And I should see the text "Slide 1 of 1"
    And I should see a ".ct-slider__controls__previous[disabled]" element
    And I should see a ".ct-slider__controls__next[disabled]" element
