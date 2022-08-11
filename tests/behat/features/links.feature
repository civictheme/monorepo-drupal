@civictheme @civictheme_links
Feature: Check that links have the correct classes.

  @api
  Scenario: The CivicTheme Link have correct classes.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    Then I fill in "Override domains" with "http://overridden.com"
    And I press "Save configuration"
    Given "civictheme_page" content:
      | title                     | status |
      | [TEST] Page content links | 1      |
    Given "civictheme_event" content:
      | title                      | status | field_c_n_body |
      | [TEST] Event content links | 1      | <h2>[TEST] Event content</h2> <p>Praesent sapien massa, <a href="/about-us">About US</a>.</p><p>convallis a pellentesque nec, egestas non nisi <a href="http://overridden.com">Overridden</a>.</p><p>Curabitur arcu erat, accumsan id imperdiet et<a href="http://example.com">External</a>.</p>  |
    Given "civictheme_alert" content:
      | title                      | status | field_c_n_alert_type | field_c_n_body |
      | [TEST] Alert content links | 1      | status               | <h2>[TEST] Alert content</h2> <p>Praesent sapien massa, <a href="/about-us">About US</a>.</p><p>convallis a pellentesque nec, egestas non nisi <a href="http://overridden.com">Overridden</a>.</p><p>Curabitur arcu erat, accumsan id imperdiet et<a href="http://example.com">External</a>.</p>  |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page content links" has "civictheme_content" paragraph:
      | field_c_p_theme          | light                 |
      | field_c_p_content:value  | <h2>[TEST] Page content</h2> <p>Praesent sapien massa, <a href="/about-us">About US</a>.</p><p>convallis a pellentesque nec, egestas non nisi <a href="http://overridden.com">Overridden</a>.</p><p>Curabitur arcu erat, accumsan id imperdiet et<a href="http://example.com">External</a>.</p>  |
      | field_c_p_content:format | civictheme_rich_text  |
      | field_c_p_background     | 0                     |
    Then I visit "civictheme_page" "[TEST] Page content links"
    And I should see an "a[href='/about-us'].civictheme-link" element
    And I should see an "a[href='http://overridden.com'].civictheme-link" element
    And I should see an "a[href='http://example.com'].civictheme-link.civictheme-link--external" element
    Then I visit "civictheme_alert" "[TEST] Alert content links"
    And I should see an "a[href='/about-us'].civictheme-link" element
    And I should see an "a[href='http://overridden.com'].civictheme-link" element
    And I should see an "a[href='http://example.com'].civictheme-link.civictheme-link--external" element
    Then I visit "civictheme_event" "[TEST] Event content links"
    And I should see an "a[href='/about-us'].civictheme-link" element
    And I should see an "a[href='http://overridden.com'].civictheme-link" element
    And I should see an "a[href='http://example.com'].civictheme-link.civictheme-link--external" element
