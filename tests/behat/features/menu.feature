@civictheme @menu
Feature: Open external links in a new tab

  Ensure that external menu links open in a new tab.

  Background:
    Given 'Footer' menu_links:
      | title                       | enabled | uri                     |
      | [TEST] External Footer link | 1       | https://example.com     |
    Given 'Primary Navigation' menu_links:
      | title                        | enabled | uri                     |
      | [TEST] External Primary link | 1       | https://example.com     |
    Given 'Secondary Navigation' menu_links:
      | title                          | enabled | uri                     |
      | [TEST] External Secondary link | 1       | https://example.com     |

  @api
  Scenario: External menu links open in a new tab.
    Given I go to the homepage
    Then I save screenshot
    And I should see the '.civictheme-secondary-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.civictheme-primary-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.civictheme-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
