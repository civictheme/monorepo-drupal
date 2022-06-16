@themesettings
Feature: Check that custom logo settings is available in theme settings

  Background:
    Given managed file:
      | filename                | uri                                              | path           |
      | header_logo_mobile.jpg  | public://civictheme_test/header_logo_mobile.jpg  | test_image.jpg |
      | footer_logo_desktop.jpg | public://civictheme_test/footer_logo_desktop.jpg | test_image.jpg |
      | footer_logo_mobile.jpg  | public://civictheme_test/footer_logo_mobile.jpg  | test_image.jpg |

  @api @javascript
  Scenario: The CivicTheme theme settings has custom logo configuration
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme"
    Then I should see an "details#edit-logo" element
    And I should see an "input[name='logo_path']" element
    And I should see an "input[name='civictheme_header_logo_mobile']" element
    And I should see an "input[name='civictheme_footer_logo_desktop']" element
    And I should see an "input[name='civictheme_footer_logo_mobile']" element
    And I should see an "input[name='civictheme_site_logo_alt']" element

    When I fill in "Header mobile logo path" with "public://civictheme_test/header_logo_mobile.jpg"
    And I fill in "Footer desktop logo path" with "public://civictheme_test/footer_logo_desktop.jpg"
    And I fill in "Footer mobile logo path" with "public://civictheme_test/footer_logo_mobile.jpg"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/header_logo_mobile.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_desktop.jpg"
    And I should see the "div.civictheme-logo img.civictheme-image" element with the "src" attribute set to "/sites/default/files/civictheme_test/footer_logo_mobile.jpg"





