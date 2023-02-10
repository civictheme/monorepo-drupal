@p0 @civictheme @civictheme_theme_settings @civictheme_migration
Feature: Components settings are available in the theme settings

  Background:
    Given managed file:
      | filename                    | uri                                                  | path           |
      | logo_light_desktop.jpg      | public://civictheme_test/logo_light_desktop.jpg      | test_image.jpg |
      | logo_light_mobile.jpg       | public://civictheme_test/logo_light_mobile.jpg       | test_image.jpg |
      | logo_dark_desktop.jpg       | public://civictheme_test/logo_dark_desktop.jpg       | test_image.jpg |
      | logo_dark_mobile.jpg        | public://civictheme_test/logo_dark_mobile.jpg        | test_image.jpg |
      | footer_background_image.jpg | public://civictheme_test/footer_background_image.jpg | test_image.jpg |

  @api @basetheme
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
    Given "civictheme_page" content:
      | title         | status | field_c_n_vertical_spacing | field_c_n_show_toc | field_c_n_show_last_updated | field_c_n_hide_sidebar | field_c_n_custom_last_updated | field_c_n_banner_type | field_c_n_banner_theme | field_c_n_banner_hide_breadcrumb |
      | [TEST] Page 2 | 1      | bottom                     | 0                  | 0                           | 1                      | 2022-07-01                    | large                 | dark                   | 0                                |
    When I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme"
    And I check the box "Expose Migration metadata"
    And I press "Save configuration"

    And I visit "civictheme_page" "[TEST] Page 1"
    Then should see a "section[data-ct-migrate-vertical-spacing='top']" element
    And should see a "section[data-ct-migrate-show-toc='1']" element
    And should see a "section[data-ct-migrate-summary='[TEST] Summary']" element
    And should see a "section[data-ct-migrate-thumbnail]" element
    And should see a "section[data-ct-migrate-topics='[TEST] Topic 1,[TEST] Topic 2,[TEST] Topic 3']" element
    And should see a "section[data-ct-migrate-show-last-updated='1']" element
    And should see a "section[data-ct-migrate-hide-sidebar='0']" element
    And should see a "section[data-ct-migrate-last-updated='1 Jul 2022']" element
    And should see a ".ct-banner[data-ct-migrate-banner-blend-mode='luminosity']" element
    And should see a ".ct-banner[data-ct-migrate-banner-background-image]" element
    And should see a ".ct-banner[data-ct-migrate-banner-type='default']" element
    And should see a ".ct-banner[data-ct-migrate-banner-theme='light']" element
    And should see a ".ct-banner[data-ct-migrate-banner-hide-breadcrumb='1']" element

    And I visit "civictheme_page" "[TEST] Page 2"
    Then should see a "section[data-ct-migrate-vertical-spacing='bottom']" element
    And should not see a "section[data-ct-migrate-summary]" element
    And should not see a "section[data-ct-migrate-thumbnail]" element
    And should not see a "section[data-ct-migrate-topics]" element
    And should see a "section[data-ct-migrate-show-toc='0']" element
    And should see a "section[data-ct-migrate-show-last-updated='0']" element
    And should see a "section[data-ct-migrate-hide-sidebar='1']" element
    And should not see a "section[data-ct-migrate-last-updated]" element
    # CivicTheme has default background image for banner.
    And should see a ".ct-banner[data-ct-migrate-banner-blend-mode='soft-light']" element
    And should see a ".ct-banner[data-ct-migrate-banner-background-image]" element
    And should see a ".ct-banner[data-ct-migrate-banner-type='large']" element
    And should see a ".ct-banner[data-ct-migrate-banner-theme='dark']" element
    And should see a ".ct-banner[data-ct-migrate-banner-hide-breadcrumb='0']" element

    And I visit "/admin/appearance/settings/civictheme"
    And I uncheck the box "Expose Migration metadata"
    And I press "Save configuration"

    And I visit "civictheme_page" "[TEST] Page 1"
    Then should not see a "section[data-ct-migrate-vertical-spacing]" element
    And should not see a "section[data-ct-migrate-show-toc]" element
    And should not see a "section[data-ct-migrate-show-last-updated]" element
    And should not see a "section[data-ct-migrate-hide-sidebar]" element
    And should not see a "section[data-ct-migrate-last-updated]" element
    And should not see a ".ct-banner[data-ct-migrate-banner-blend-mode]" element
    And should not see a ".ct-banner[data-ct-migrate-banner-background-image]" element
    And should not see a ".ct-banner[data-ct-migrate-banner-type]" element
    And should not see a ".ct-banner[data-ct-migrate-banner-theme]" element
    And should not see a ".ct-banner[data-ct-migrate-banner-hide-breadcrumb]" element
    And should not see a "section[data-ct-migrate-summary]" element
    And should not see a "section[data-ct-migrate-thumbnail]" element
    And should not see a "section[data-ct-migrate-topics]" element
