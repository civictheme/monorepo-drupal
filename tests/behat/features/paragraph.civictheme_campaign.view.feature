@civictheme @paragraph @civictheme_campaign
Feature: View of Page content with Campaign component

  Ensure that Page content can be viewed correctly with Campaign component.

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    Given "civictheme_page" content:
      | title                       | status |
      | [TEST] Page Campaign test   | 1      |
      | [TEST] Page Campaign test 1 | 1      |

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with Campaign light with vertical spacing and right image
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Campaign steps test" has "civictheme_campaign" paragraph:
      | field_c_p_title            | [TEST] Campaign title   |
      | field_c_p_theme            | light                   |
      | field_c_p_summary          | Summary text            |
      | field_c_p_vertical_spacing | both                    |
      | field_c_p_date             | 2021-04-29              |
      | field_c_p_topic            | [TEST] Topic 1          |
      | field_c_p_image            | [TEST] CivicTheme Image |
      | field_c_p_image_position   | right                   |

    When I visit "civictheme_page" "[TEST] Campaign steps test"
    And I scroll to an element with id "main-content"
    And I should see an "div.ct-campaign" element
    And I should see an "div.ct-campaign.ct-theme-light" element
    And I should not see an ".ct-campaign.ct-theme-dark" element
    And I should see an "div.ct-campaign.ct-vertical-spacing-inset--both" element
    And I should see an "div.ct-campaign.ct-campaign--image-position-right" element
    And I should see an "div.ct-campaign__content" element
    And I should see an "div.ct-campaign__tags" element
    And I should see an "div.ct-campaign__date" element
    And I should see the text "[TEST] Campaign title"
    And I should see an "div.ct-campaign__title" element
    And I should see an "div.ct-campaign__summary" element
    And I should see an "div.ct-campaign__image-wrapper" element
    And I should see the text "29 Apr 2021"

  @api @javascript
  Scenario: CivicTheme page content type page can be viewed by anonymous with Campaign dark with no vertical spacing and left image
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Campaign steps test" has "civictheme_campaign" paragraph:
      | field_c_p_title            | [TEST] Campaign title   |
      | field_c_p_theme            | dark                    |
      | field_c_p_summary          | Summary text            |
      | field_c_p_vertical_spacing | 0                       |
      | field_c_p_date             | 2022-06-30              |
      | field_c_p_topic            | [TEST] Topic 1          |
      | field_c_p_image            | [TEST] CivicTheme Image |
      | field_c_p_image_position   | left                    |

    When I visit "civictheme_page" "[TEST] Campaign steps test"
    And I scroll to an element with id "main-content"
    And I should see an "div.ct-campaign" element
    And I should not see an "div.ct-campaign.ct-theme-light" element
    And I should see an "div.ct-campaign.ct-theme-dark" element
    And I should not see an "div.ct-campaign.ct-vertical-spacing-inset--both" element
    And I should see an "div.ct-campaign.ct-campaign--image-position-left" element
    And I should see an "div.ct-campaign__content" element
    And I should see an "div.ct-campaign__tags" element
    And I should see an "div.ct-campaign__date" element
    And I should see the text "[TEST] Campaign title"
    And I should see an "div.ct-campaign__title" element
    And I should see an "div.ct-campaign__summary" element
    And I should see the text "30 Jun 2022"
