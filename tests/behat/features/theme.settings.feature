@civictheme @civictheme_theme_settings
Feature: Check that custom settings is available in theme settings

  Background:
    Given managed file:
      | filename                | uri                                              | path           |
      | header_logo_desktop.jpg | public://civictheme_test/header_logo_desktop.jpg | test_image.jpg |
      | header_logo_mobile.jpg  | public://civictheme_test/header_logo_mobile.jpg  | test_image.jpg |
      | footer_logo_desktop.jpg | public://civictheme_test/footer_logo_desktop.jpg | test_image.jpg |
      | footer_logo_mobile.jpg  | public://civictheme_test/footer_logo_mobile.jpg  | test_image.jpg |

  @api @javascript
  Scenario: The CivicTheme theme settings has custom fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"
    And I should see an "details#edit-logo" element
    And I see field "Header desktop logo path"
    And I should see an "input[name='logo_path']" element
    And should see an "input#edit-logo-path" element
    And should not see an "input#edit-logo-path.required" element
    And I see field "Header mobile logo path"
    And I should see an "input[name='civictheme_header_logo_mobile']" element
    And should see an "input#edit-civictheme-header-logo-mobile" element
    And should not see an "input#edit-civictheme-header-logo-mobile.required" element
    And I see field "Footer desktop logo path"
    And I should see an "input[name='civictheme_footer_logo_desktop']" element
    And should see an "input#edit-civictheme-footer-logo-desktop" element
    And should not see an "input#edit-civictheme-footer-logo-desktop.required" element
    And I see field "Footer mobile logo path"
    And I should see an "input[name='civictheme_footer_logo_mobile']" element
    And should see an "input#edit-civictheme-footer-logo-mobile" element
    And should not see an "input#edit-civictheme-footer-logo-mobile.required" element
    And I see field "Logo alt attribute text"
    And I should see an "input[name='civictheme_site_logo_alt']" element
    And should see an "input#edit-civictheme-site-logo-alt" element
    And should not see an "input#edit-civictheme-site-logo-alt.required" element
    And I see field "Footer background image path"
    And I should see an "input[name='civictheme_site_logo_alt']" element
    And should see an "input#edit-civictheme-footer-background-image" element
    And should not see an "input#edit-civictheme-footer-background-image.required" element

@api @javascript
  Scenario: The CivicTheme theme settings verify custom logo configuration with stream wrapper
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"
    When I fill in "Header desktop logo path" with "public://civictheme_test/header_logo_desktop.jpg"
    When I fill in "Header mobile logo path" with "public://civictheme_test/header_logo_mobile.jpg"
    When I fill in "Footer desktop logo path" with "public://civictheme_test/footer_logo_desktop.jpg"
    When I fill in "Footer mobile logo path" with "public://civictheme_test/footer_logo_mobile.jpg"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_mobile.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_mobile.jpg"

@api @javascript
  Scenario: The CivicTheme theme settings verify custom logo configuration with static assets
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"
    When I fill in "Header desktop logo path" with "sites/default/files/civictheme_test/header_logo_desktop.jpg"
    When I fill in "Header mobile logo path" with "sites/default/files/civictheme_test/header_logo_mobile.jpg"
    When I fill in "Footer desktop logo path" with "sites/default/files/civictheme_test/footer_logo_desktop.jpg"
    When I fill in "Footer mobile logo path" with "sites/default/files/civictheme_test/footer_logo_mobile.jpg"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_mobile.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_mobile.jpg"

@api @javascript
  Scenario: The CivicTheme theme settings verify failed custom logo configuration with static assets
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"
    When I fill in "Header desktop logo path" with "/sites/default/files/civictheme_test/header_logo_desktop.jpg"
    And I press "Save configuration"
    And I should see the text "1 error has been found: Header desktop logo path"
