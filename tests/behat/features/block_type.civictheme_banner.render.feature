@p0 @civictheme @block_type @block_civictheme_banner
Feature: Banner render

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | image.jpg      | public://civictheme_test/image.jpg      | image.jpg      |

    And "civictheme_image" media:
      | name                            | field_c_m_image |
      | [TEST] CivicTheme Block Image   | test_image.jpg  |
      | [TEST] CivicTheme Content Image | image.jpg       |

    And "civictheme_page" content:
      | title                            | status | field_c_n_banner_type | field_c_n_banner_featured_image |
      | [TEST] Page banner test          | 1      |                       |                                 |
      | [TEST] Page banner override test | 1      | large                 | [TEST] CivicTheme Content Image |

  @api @javascript
  Scenario: CivicTheme Page banner with default values.
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Page banner test"
    And save screenshot

  @api @javascript
  Scenario: CivicTheme Page banner with override values.
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Page banner override test"
    And I should see an ".ct-banner__featured-image" element
    And I should see an ".ct-banner.ct-banner--decorative" element
    And save screenshot
