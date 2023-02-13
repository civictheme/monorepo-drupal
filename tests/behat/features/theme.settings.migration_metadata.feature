@p0 @civictheme @civictheme_theme_settings @civictheme_theme_settings_migration @civictheme_migration
Feature: Migration metadata is available on selected components.

  @api
  Scenario: The CivicTheme theme setting `Expose Migration metadata` exposes meta data in DOM
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
      | [TEST] Topic 3 |
    Given "civictheme_page" content:
      | title         | status | field_c_n_summary | field_c_n_topics                               | field_c_n_thumbnail     | field_c_n_vertical_spacing | field_c_n_show_toc | field_c_n_show_last_updated | field_c_n_hide_sidebar | field_c_n_custom_last_updated | field_c_n_banner_background | field_c_n_blend_mode | field_c_n_banner_type | field_c_n_banner_theme | field_c_n_banner_hide_breadcrumb |
      | [TEST] Page 1 | 1      | [TEST] Summary    | [TEST] Topic 1, [TEST] Topic 2, [TEST] Topic 3 | [TEST] CivicTheme Image | top                        | 1                  | 1                           | 0                      | 2022-07-01                    | [TEST] CivicTheme Image     | luminosity           | default               | light                  | 1                                |
    And "civictheme_page" content:
      | title         | status | field_c_n_vertical_spacing | field_c_n_show_toc | field_c_n_show_last_updated | field_c_n_hide_sidebar | field_c_n_custom_last_updated | field_c_n_banner_type | field_c_n_banner_theme | field_c_n_banner_hide_breadcrumb |
      | [TEST] Page 2 | 1      | bottom                     | 0                  | 0                           | 1                      | 2022-07-01                    | large                 | dark                   | 0                                |

    When I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    # Reset settings.
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

    And I check the box "Expose Migration metadata"
    And I press "Save configuration"

    And I visit "civictheme_page" "[TEST] Page 1"
    Then should see a "[data-ct-migrate-node-vertical-spacing='top']" element
    And should see a "[data-ct-migrate-node-show-toc='1']" element
    And should see a "[data-ct-migrate-node-summary='[TEST] Summary']" element
    And should see a "[data-ct-migrate-node-thumbnail]" element
    And should see a "[data-ct-migrate-node-topics='[TEST] Topic 1,[TEST] Topic 2,[TEST] Topic 3']" element
    And should see a "[data-ct-migrate-node-show-last-updated='1']" element
    And should see a "[data-ct-migrate-node-hide-sidebar='0']" element
    And should see a "[data-ct-migrate-node-last-updated='1 Jul 2022']" element
    And should see a "[data-ct-migrate-banner-background-image-blend-mode='luminosity']" element
    And should see a "[data-ct-migrate-banner-background-image]" element
    And should see a "[data-ct-migrate-banner-type='default']" element
    And should see a "[data-ct-migrate-banner-theme='light']" element
    And should see a "[data-ct-migrate-banner-hide-breadcrumb='1']" element

    When I visit "civictheme_page" "[TEST] Page 2"
    Then should see a "[data-ct-migrate-node-vertical-spacing='bottom']" element
    And should not see a "[data-ct-migrate-node-summary]" element
    And should not see a "[data-ct-migrate-node-thumbnail]" element
    And should not see a "[data-ct-migrate-node-topics]" element
    And should see a "[data-ct-migrate-node-show-toc='0']" element
    And should see a "[data-ct-migrate-node-show-last-updated='0']" element
    And should see a "[data-ct-migrate-node-hide-sidebar='1']" element
    And should not see a "[data-ct-migrate-node-last-updated]" element
    # CivicTheme has default background image for banner.
    And should see a "[data-ct-migrate-banner-background-image-blend-mode='soft-light']" element
    And should see a "[data-ct-migrate-banner-background-image]" element
    And should see a "[data-ct-migrate-banner-type='large']" element
    And should see a "[data-ct-migrate-banner-theme='dark']" element
    And should see a "[data-ct-migrate-banner-hide-breadcrumb='0']" element

    When I visit civictheme theme settings page
    And I uncheck the box "Expose Migration metadata"
    And I press "Save configuration"

    And I visit "civictheme_page" "[TEST] Page 1"
    Then should not see a "[data-ct-migrate-node-vertical-spacing='top']" element
    And should not see a "[data-ct-migrate-node-show-toc='1']" element
    And should not see a "[data-ct-migrate-node-summary='[TEST] Summary']" element
    And should not see a "[data-ct-migrate-node-thumbnail]" element
    And should not see a "[data-ct-migrate-node-topics='[TEST] Topic 1,[TEST] Topic 2,[TEST] Topic 3']" element
    And should not see a "[data-ct-migrate-node-show-last-updated='1']" element
    And should not see a "[data-ct-migrate-node-hide-sidebar='0']" element
    And should not see a "[data-ct-migrate-node-last-updated='1 Jul 2022']" element
    And should not see a "[data-ct-migrate-banner-background-image-blend-mode='luminosity']" element
    And should not see a "[data-ct-migrate-banner-background-image]" element
    And should not see a "[data-ct-migrate-banner-type='default']" element
    And should not see a "[data-ct-migrate-banner-theme='light']" element
    And should not see a "[data-ct-migrate-banner-hide-breadcrumb='1']" element

    When I visit "civictheme_page" "[TEST] Page 2"
    Then should not see a "[data-ct-migrate-node-vertical-spacing='bottom']" element
    And should not see a "[data-ct-migrate-node-summary]" element
    And should not see a "[data-ct-migrate-node-thumbnail]" element
    And should not see a "[data-ct-migrate-node-topics]" element
    And should not see a "[data-ct-migrate-node-show-toc='0']" element
    And should not see a "[data-ct-migrate-node-show-last-updated='0']" element
    And should not see a "[data-ct-migrate-node-hide-sidebar='1']" element
    And should not see a "[data-ct-migrate-node-last-updated]" element
    # CivicTheme has default background image for banner.
    And should not see a "[data-ct-migrate-banner-background-image-blend-mode='soft-light']" element
    And should not see a "[data-ct-migrate-banner-background-image]" element
    And should not see a "[data-ct-migrate-banner-type='large']" element
    And should not see a "[data-ct-migrate-banner-theme='dark']" element
    And should not see a "[data-ct-migrate-banner-hide-breadcrumb='0']" element
