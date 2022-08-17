@civictheme @civictheme_links
Feature: Check that links have the correct classes.

  @api @javascript
  Scenario: The CivicTheme Link have correct classes.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    Then I fill in "Override domains" with "http://overridden.com"
    And I press "Save configuration"
    Given "civictheme_page" content:
      | title                     | status |
      | [TEST] Page content links | 1      |
    Given "civictheme_alert" content:
      | title                      | status | field_c_n_alert_type | field_c_n_body |
      | [TEST] Alert content links | 1      | error                | <h2>[TEST] Alert content</h2> <a href="/about-us">About US</a> <a href="http://overridden.com">Overridden</a> <a href="http://example.com">External</a> |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content links" has "civictheme_accordion" paragraph:
      | field_c_p_theme          | light                              |
      | field_c_p_background     | 0                                  |
      | field_c_p_expand         | 0                                  |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content links" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 1              |
      | field_c_p_expand         | 0                                     |
      | field_c_p_content:value  | <h2>[TEST] content</h2> <a href="/about-us">About US</a> <a href="http://overridden.com">Overridden</a> <a href="http://example.com">External</a> |
      | field_c_p_content:format | civictheme_rich_text                  |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content links" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 2              |
      | field_c_p_expand         | 0                                     |
      | field_c_p_content:value  | <h2>[TEST] content</h2> <a href="/about-us">About US</a> <a href="http://overridden.com">Overridden</a> <a href="http://example.com">External</a> |
      | field_c_p_content:format | civictheme_rich_text                  |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content links" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                 |
      | field_c_p_content:value  | <h2>[TEST] Page content</h2> <a href="/about-us">About US</a> <a href="http://overridden.com">Overridden</a> <a href="http://example.com">External</a> |
      | field_c_p_content:format | civictheme_rich_text  |
      | field_c_p_background     | 0                     |
    Then I visit "civictheme_page" "[TEST] Page content links"
    And I should see an ".civictheme-basic-content a[href='/about-us'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://overridden.com'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://example.com'].civictheme-link.civictheme-link--external" element
    And I should see an ".civictheme-accordion a[href='/about-us'].civictheme-link" element
    And I should see an ".civictheme-accordion a[href='http://overridden.com'].civictheme-link" element
    And I should see an ".civictheme-accordion a[href='http://example.com'].civictheme-link.civictheme-link--external" element
    Then I go to the homepage
    And wait 5 second
    And I wait for AJAX to finish
    And I should see an ".civictheme-alert a[href='/about-us'].civictheme-link" element
    And I should see an ".civictheme-alert a[href='http://example.com'].civictheme-link.civictheme-link--external" element
    And I should see an ".civictheme-alert a[href='http://overridden.com'].civictheme-link" element
