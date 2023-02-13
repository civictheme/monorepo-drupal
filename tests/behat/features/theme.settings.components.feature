@p0 @civictheme @civictheme_theme_settings @civictheme_theme_settings_components
Feature: Components settings are available in the theme settings

  Background:
    Given managed file:
      | filename                    | uri                                                  | path           |
      | logo_light_desktop.jpg      | public://civictheme_test/logo_light_desktop.jpg      | test_image.jpg |
      | logo_light_mobile.jpg       | public://civictheme_test/logo_light_mobile.jpg       | test_image.jpg |
      | logo_dark_desktop.jpg       | public://civictheme_test/logo_dark_desktop.jpg       | test_image.jpg |
      | logo_dark_mobile.jpg        | public://civictheme_test/logo_dark_mobile.jpg        | test_image.jpg |
      | footer_background_image.jpg | public://civictheme_test/footer_background_image.jpg | test_image.jpg |

  @api
  Scenario: The CivicTheme theme settings form has Component fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit current theme settings page

    And I should not see an "input#edit-toggle-node-user-picture" element
    And I should not see an "input#edit-toggle-comment-user-picture" element
    And I should not see an "input#edit-toggle-comment-user-verification" element
    And I should see an "input#edit-toggle-favicon" element

    And I see field "Image path for Primary Light logo for Desktop"
    And I should see an "input#edit-components-logo-primary-light-desktop-path" element
    And I should not see an "input#edit-components-logo-primary-light-desktop-path.required" element

    And I see field "Image path for Primary Light logo for Mobile"
    And I should see an "input#edit-components-logo-primary-light-mobile-path" element
    And I should not see an "input#edit-components-logo-primary-light-mobile-path.required" element

    And I see field "Image path for Primary Dark logo for Desktop"
    And I should see an "input#edit-components-logo-primary-dark-desktop-path" element
    And I should not see an "input#edit-components-logo-primary-dark-desktop-path.required" element

    And I see field "Image path for Primary Dark logo for Mobile"
    And I should see an "input#edit-components-logo-primary-dark-mobile-path" element
    And I should not see an "input#edit-components-logo-primary-dark-mobile-path.required" element

    And I see field "Image path for Secondary Light logo for Desktop"
    And I should see an "input#edit-components-logo-secondary-light-desktop-path" element
    And I should not see an "input#edit-components-logo-secondary-light-desktop-path.required" element

    And I see field "Image path for Secondary Light logo for Mobile"
    And I should see an "input#edit-components-logo-secondary-light-mobile-path" element
    And I should not see an "input#edit-components-logo-secondary-light-mobile-path.required" element

    And I see field "Image path for Secondary Dark logo for Desktop"
    And I should see an "input#edit-components-logo-secondary-dark-desktop-path" element
    And I should not see an "input#edit-components-logo-secondary-dark-desktop-path.required" element

    And I see field "Image path for Secondary Dark logo for Mobile"
    And I should see an "input#edit-components-logo-secondary-dark-mobile-path" element
    And I should not see an "input#edit-components-logo-secondary-dark-mobile-path.required" element

    And I see field 'Logo image "alt" text'
    And I should see an "input[name='components[logo][image_alt]']" element
    And I should not see an "edit-components-logo-alt.required" element

    And I should see the text "Theme"
    And I should see an "input[name='components[header][theme]']" element
    And I should see an "#edit-components-header-theme--wrapper.required" element

    And I should see the text "Theme"
    And I should see an "input[name='components[footer][theme]']" element
    And I should see an "#edit-components-footer-theme--wrapper.required" element

    And I see field "Footer background image path"
    And should see an "input#edit-components-footer-background-image-path" element
    And should not see an "input#edit-components-footer-background-image-path.required" element

    And I should see the text "Primary navigation"
    And I should see an "select[name='components[navigation][primary_navigation][dropdown]']" element
    And I should see an "select[name='components[navigation][primary_navigation][dropdown]'].required" element

    And I should see the text "Number of columns in the drawer row"
    And I should see an "input[name='components[navigation][primary_navigation][dropdown_columns]']" element
    And I should not see an "input[name='components[navigation][primary_navigation][dropdown_columns]'].required" element

    And I should see the text "Fill width of the last drawer column"
    And I should see an "input[name='components[navigation][primary_navigation][dropdown_columns_fill]']" element
    And I should not see an "input[name='components[navigation][primary_navigation][dropdown_columns_fill]'].required" element

    And I should see the text "Animate"
    And I should see an "input[name='components[navigation][primary_navigation][is_animated]']" element
    And I should not see an "input[name='components[navigation][primary_navigation][is_animated]'].required" element

    And I should see the text "Secondary navigation"
    And I should see an "select[name='components[navigation][secondary_navigation][dropdown]']" element
    And I should see an "select[name='components[navigation][secondary_navigation][dropdown]'].required" element

    And I should see the text "Number of columns in the drawer row"
    And I should see an "input[name='components[navigation][secondary_navigation][dropdown_columns]']" element
    And I should not see an "input[name='components[navigation][secondary_navigation][dropdown_columns]'].required" element

    And I should see the text "Fill width of the last drawer column"
    And I should see an "input[name='components[navigation][secondary_navigation][dropdown_columns_fill]']" element
    And I should not see an "input[name='components[navigation][secondary_navigation][dropdown_columns_fill]'].required" element

    And I should see the text "Animate"
    And I should see an "input[name='components[navigation][secondary_navigation][is_animated]']" element
    And I should not see an "input[name='components[navigation][secondary_navigation][is_animated]'].required" element

    And I should see the text "Open links in a new window"
    And should see an "input#edit-components-link-new-window" element
    And should not see an "#edit-components-link-new-window.required" element

    And I should see the text "Open external links in a new window"
    And should see an "input#edit-components-link-external-new-window" element
    And should not see an "#edit-components-link-external-new-window.required" element

    And I see field "Override external link domains"
    And should see an "textarea#edit-components-link-external-override-domains" element
    And should not see an "textarea#edit-components-link-external-override-domains.required" element

    And I should see the text "Theme"
    And I should see an "input[name='components[skip_link][theme]']" element
    And I should see an "#edit-components-skip-link-theme--wrapper.required" element

  @api
  Scenario: The CivicTheme theme settings verify custom logo configuration with a stream wrapper
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I fill in "Image path for Primary Light logo for Desktop" with "public://civictheme_test/logo_light_desktop.jpg"
    And I fill in "Image path for Primary Light logo for Mobile" with "public://civictheme_test/logo_light_mobile.jpg"
    And I fill in "Image path for Primary Dark logo for Desktop" with "public://civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Image path for Primary Dark logo for Mobile" with "public://civictheme_test/logo_dark_mobile.jpg"
    And I select the radio button "Light" with the id "edit-components-header-theme-light"
    And I select the radio button "Default" with the id "edit-components-header-logo-type-default"
    And I select the radio button "Dark" with the id "edit-components-footer-theme-dark"
    And I select the radio button "Default" with the id "edit-components-footer-logo-type-default"
    And I fill in "Footer background image path" with "public://civictheme_test/footer_background_image.jpg"
    And I press "Save configuration"

    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the ".ct-header .ct-logo .ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I should see the ".ct-header .ct-logo .ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I should see the ".ct-logo .ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I should see the ".ct-logo .ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"

    # Reset settings.
    When I visit current theme settings page
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

  @api
  Scenario: The CivicTheme theme settings verify custom logo configuration with static assets
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I fill in "Image path for Primary Light logo for Desktop" with "sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I fill in "Image path for Primary Light logo for Mobile" with "sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I fill in "Image path for Primary Dark logo for Desktop" with "sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Image path for Primary Dark logo for Mobile" with "sites/default/files/civictheme_test/logo_dark_mobile.jpg"
    And I fill in "Footer background image path" with "sites/default/files/civictheme_test/footer_background_image.jpg"
    And I press "Save configuration"

    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"

    # Assert with static assets prefixed with '/'.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I fill in "Image path for Primary Light logo for Desktop" with "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I fill in "Image path for Primary Light logo for Mobile" with "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I fill in "Image path for Primary Dark logo for Desktop" with "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Image path for Primary Dark logo for Mobile" with "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"
    And I fill in "Image path for Secondary Light logo for Desktop" with "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I fill in "Image path for Secondary Light logo for Mobile" with "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I fill in "Image path for Secondary Dark logo for Desktop" with "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Image path for Secondary Dark logo for Mobile" with "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"
    And I fill in "Footer background image path" with "/sites/default/files/civictheme_test/footer_background_image.jpg"
    And I press "Save configuration"

    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I should see the ".ct-logo img.ct-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"

    # Reset settings.
    When I visit current theme settings page
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

  @api
  Scenario: The CivicTheme theme settings verify custom logo configuration with image upload
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I attach the file "test_image_logo_light_desktop.jpg" to "Upload Primary Light logo for Desktop"
    And I attach the file "test_image_logo_light_mobile.jpg" to "Upload Primary Light logo for Mobile"
    And I attach the file "test_image_logo_dark_desktop.jpg" to "Upload Primary Dark logo for Desktop"
    And I attach the file "test_image_logo_dark_mobile.jpg" to "Upload Primary Dark logo for Mobile"
    And I select the radio button "Light" with the id "edit-components-header-theme-light"
    And I select the radio button "Default" with the id "edit-components-header-logo-type-default"
    And I select the radio button "Dark" with the id "edit-components-footer-theme-dark"
    And I select the radio button "Default" with the id "edit-components-footer-logo-type-default"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."

    # Reset settings.
    When I visit current theme settings page
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

  @api
  Scenario: The CivicTheme theme settings External Links component validation works.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page

    When I fill in "Override external link domains" with "http://exampleoverridden.com"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."

    When I fill in "Override external link domains" with "http//invaliddomain.com"
    And I press "Save configuration"
    Then I should not see the text "The configuration options have been saved."

    # Reset settings.
    When I visit current theme settings page
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."

  @api
  Scenario: The CivicTheme theme settings Skip Link works.
    Given I am logged in as a user with the "Site Administrator" role

    When I visit current theme settings page
    And  I select the radio button "Light" with the id "edit-components-skip-link-theme-light"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see a ".ct-skip-link.ct-theme-light" element

    When I visit current theme settings page
    And I select the radio button "Dark" with the id "edit-components-skip-link-theme-dark"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see a ".ct-skip-link.ct-theme-dark" element

    # Reset settings.
    When I visit current theme settings page
    And I check the box "Confirm settings reset"
    And I press "reset_to_defaults"
    Then I should see the text "Theme configuration was reset to defaults."
