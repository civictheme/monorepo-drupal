@civictheme @civictheme_theme_settings
Feature: Check that components settings are available in theme settings

  @api
  Scenario: The CivicTheme theme settings form has Component fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/appearance/settings/civictheme_demo"

    And I should see the text "Header theme"
    And I should see an "input[name='civictheme_header_theme']" element
    And I should see an "#edit-civictheme-header-theme--wrapper.required" element

    And I should see the text "Footer theme"
    And I should see an "input[name='civictheme_footer_theme']" element
    And I should see an "#edit-civictheme-footer-theme--wrapper.required" element

    And I see field "Footer background image path"
    And should see an "input#edit-civictheme-footer-background-image" element
    And should not see an "input#edit-civictheme-footer-background-image.required" element

    And I see field "Override domains"
    And should see an "textarea#edit-civictheme-external-link-override-domains" element
    And should not see an "textarea#edit-civictheme-external-link-override-domains.required" element

  @api
  Scenario: The CivicTheme theme settings External Links comnponent validation works.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"

    When I fill in "Override domains" with "http://exampleoverridden.com"
    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."

    When I fill in "Override domains" with "http//invaliddomain.com"
    And I press "Save configuration"
    Then I should not see the text "The configuration options have been saved."
