@civictheme @civictheme_theme_settings
Feature: Check that custom settings is available in theme settings

  Background:
    Given managed file:
      | filename                | uri                                               | path           |
      | header_logo_desktop.jpg  | public://civictheme_test/header_logo_desktop.jpg | test_image.jpg |
      | header_logo_mobile.jpg  | public://civictheme_test/header_logo_mobile.jpg   | test_image.jpg |
      | footer_logo_desktop.jpg | public://civictheme_test/footer_logo_desktop.jpg  | test_image.jpg |
      | footer_logo_mobile.jpg  | public://civictheme_test/footer_logo_mobile.jpg   | test_image.jpg |

  @api @javascript
  Scenario Outline: The CivicTheme theme settings has custom logo configuration
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "<path>"
    Then the response status code should be "<response>"
    And I should see an "details#edit-logo" element
    And I see field "Header desktop logo path"
    And I should see an "input[name='logo_path']" element
    And should see an "input#edit-logo-path" element
    And should not see an "input#edit-logo-path.required" element
    And I see field "Header mobile logo path"
    And I should see an "input[name='civictheme_header_logo_mobile']" element
    And should see an "inputedit-civictheme-header-logo-mobile" element
    And should not see an "inputedit-civictheme-header-logo-mobile.required" element
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
    And I uncheck the filter chip "Use the logo supplied by the theme"
    When I fill in "Header desktop logo path" with "public://civictheme_test/header_logo_desktop.jpg"
    When I fill in "Header mobile logo path" with "public://civictheme_test/header_logo_mobile.jpg"
    And I fill in "Footer desktop logo path" with "public://civictheme_test/footer_logo_desktop.jpg"
    And I fill in "Footer mobile logo path" with "public://civictheme_test/footer_logo_mobile.jpg"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_mobile.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_mobile.jpg"

    Examples:
      | path                                       | response |
      | /admin/appearance/settings/civictheme      | 200      |
      | /admin/appearance/settings/civictheme_demo | 200      |
