@civictheme @civictheme_theme_settings
Feature: Check that components settings are available in theme settings

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
    When I visit "/admin/appearance/settings/civictheme_demo"

    And I should not see an "input#edit-toggle-node-user-picture" element
    And I should not see an "input#edit-toggle-comment-user-picture" element
    And I should not see an "input#edit-toggle-comment-user-verification" element
    And I should see an "input#edit-toggle-favicon" element

    And I see field "Logo image in Light theme for desktop"
    And I should see an "input#edit-components-logo-image-light-desktop" element
    And I should not see an "input#edit-components-logo-image-light-desktop.required" element

    And I see field "Logo image in Light theme for mobile"
    And I should see an "input#edit-components-logo-image-light-mobile" element
    And I should not see an "input#edit-components-logo-image-light-mobile.required" element

    And I see field "Logo image in Dark theme for desktop"
    And I should see an "input#edit-components-logo-image-dark-desktop" element
    And I should not see an "input#edit-components-logo-image-dark-desktop.required" element

    And I see field "Logo image in Dark theme for mobile"
    And I should see an "input#edit-components-logo-image-dark-mobile" element
    And I should not see an "input#edit-components-logo-image-dark-mobile.required" element

    And I see field 'Logo image "alt" text'
    And I should see an "input[name='components[logo][image_alt]']" element
    And I should not see an "edit-components-logo-image-alt.required" element

    And I should see the text "Header theme"
    And I should see an "input[name='components[header][theme]']" element
    And I should see an "#edit-components-header-theme--wrapper.required" element

    And I should see the text "Footer theme"
    And I should see an "input[name='components[footer][theme]']" element
    And I should see an "#edit-components-footer-theme--wrapper.required" element

    And I see field "Footer background image path"
    And should see an "input#edit-components-footer-background-image" element
    And should not see an "input#edit-components-footer-background-image.required" element

  @api
  Scenario: The CivicTheme theme settings verify custom logo configuration with stream wrapper
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    When I fill in "Logo image in Light theme for desktop" with "public://civictheme_test/logo_light_desktop.jpg"
    And I fill in "Logo image in Light theme for mobile" with "public://civictheme_test/logo_light_mobile.jpg"
    And I fill in "Logo image in Dark theme for desktop" with "public://civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Logo image in Dark theme for mobile" with "public://civictheme_test/logo_dark_mobile.jpg"
    And I fill in "Footer background image path" with "public://civictheme_test/footer_background_image.jpg"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the ".civictheme-header .civictheme-logo .civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I should see the ".civictheme-header .civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"

  @api
  Scenario: The CivicTheme theme settings verify custom logo configuration with static assets
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    When I fill in "Logo image in Light theme for desktop" with "sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I fill in "Logo image in Light theme for mobile" with "sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I fill in "Logo image in Dark theme for desktop" with "sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Logo image in Dark theme for mobile" with "sites/default/files/civictheme_test/logo_dark_mobile.jpg"
    And I fill in "Footer background image path" with "sites/default/files/civictheme_test/footer_background_image.jpg"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"

  @api
  Scenario: The CivicTheme theme settings verify failed custom logo configuration with static assets
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    When I fill in "Logo image in Light theme for desktop" with "/sites/default/files/civictheme_test/logo_light_desktop.jpg"
    And I fill in "Logo image in Light theme for mobile" with "/sites/default/files/civictheme_test/logo_light_mobile.jpg"
    And I fill in "Logo image in Dark theme for desktop" with "/sites/default/files/civictheme_test/logo_dark_desktop.jpg"
    And I fill in "Logo image in Dark theme for mobile" with "/sites/default/files/civictheme_test/logo_dark_mobile.jpg"
    And I fill in "Footer background image path" with "/sites/default/files/civictheme_test/footer_background_image.jpg"
    And I press "Save configuration"
    Then I should see the text "5 errors have been found"
