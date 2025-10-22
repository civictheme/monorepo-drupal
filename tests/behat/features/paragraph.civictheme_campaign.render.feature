@p1 @civictheme @civictheme_campaign
Feature: Campaign render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |

    And "civictheme_page" content:
      | title                       | status |
      | [TEST] Page Campaign test 1 | 1      |
      | [TEST] Page Campaign test 2 | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Campaign light with vertical spacing and right image
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Campaign test 1" has "civictheme_campaign" paragraph:
      | field_c_p_title            | [TEST] Campaign title                                                                            |
      | field_c_p_theme            | light                                                                                            |
      | field_c_p_content:value    | Content text                                                                                     |
      | field_c_p_content:format   | civictheme_rich_text                                                                             |
      | field_c_p_vertical_spacing | both                                                                                             |
      | field_c_p_date             | 2021-04-29                                                                                       |
      | field_c_p_topics           | [TEST] Topic 1, [TEST] Topic 2                                                                   |
      | field_c_p_links            | 0: [TEST] link 1 - 1: https://example.com/link1, 0: [TEST] link 2 - 1: https://example.com/link2 |
      | field_c_p_image            | [TEST] CivicTheme Image                                                                          |
      | field_c_p_image_position   | right                                                                                            |

    When I visit "civictheme_page" "[TEST] Page Campaign test 1"
    And I should see an ".ct-campaign" element
    And I should see an ".ct-campaign.ct-theme-light" element
    And I should not see an ".ct-campaign.ct-theme-dark" element
    And I should see an ".ct-campaign.ct-vertical-spacing-inset--both" element
    And I should see an ".ct-campaign.ct-campaign--image-right" element
    And I should see an ".ct-campaign__content" element
    And I should see an ".ct-campaign__tags" element
    And I should see an ".ct-campaign__date" element
    And I should see an ".ct-campaign__links" element
    And I should see the text "[TEST] Campaign title"
    And I should see the text "Content text"
    And I should see the text "29 Apr 2021"
    And I should see the text "[TEST] link 1"
    And I should see the text "[TEST] link 2"
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Topic 2"

  @api @security
  Scenario: XSS - Campaign
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Campaign test 1" has "civictheme_campaign" paragraph:
      | field_c_p_title            | <script id="test-campaign--field_c_p_title">alert('[TEST] Campaign field_c_p_title')</script>                                                                            |
      | field_c_p_theme            | light                                                                                            |
      | field_c_p_content:value    | <script id="test-campaign--field_c_p_content">alert('[TEST] Campaign field_c_p_content')</script>                                                                                     |
      | field_c_p_content:format   | civictheme_rich_text                                                                             |
      | field_c_p_vertical_spacing | both                                                                                             |
      | field_c_p_date             | 2021-04-29                                                                                       |
      | field_c_p_topics           | [TEST] Topic 1, [TEST] Topic 2                                                                   |
      | field_c_p_links            | 0: <script id="test-campaign--field_c_p_link--0">alert('field_c_p_link--0');</script> - 1: https://example.com/link1, 0: <script id="test-campaign--field_c_p_link--1">alert('field_c_p_link--1');</script> - 1: https://example.com/link2 |
      | field_c_p_image            | [TEST] CivicTheme Image                                                                          |
      | field_c_p_image_position   | right                                                                                            |

    When I visit "civictheme_page" "[TEST] Page Campaign test 1"
    And I should see an ".ct-campaign" element
    And I should see an ".ct-campaign.ct-theme-light" element
    And I should not see an "script#test-campaign--field_c_p_title" element
    And I should see the text "alert('[TEST] Campaign field_c_p_title')"
    And I should see an ".ct-campaign__content" element
    And I should not see an "script#test-campaign--field_c_p_content" element
    And I should see the text "alert('[TEST] Campaign field_c_p_content')"
    And I should not see an "script#test-campaign--field_c_p_link--0" element
    And I should see the text "alert('field_c_p_link--0')"
    And I should not see an "script#test-campaign--field_c_p_link--1" element
    And I should see the text "alert('field_c_p_link--1')"
