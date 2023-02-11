@p0 @civictheme @navigation
Feature: Navigation

  @api @basetheme
  Scenario: External menu links open in a new tab.
    Given "civictheme_page" content:
      | title                | status | path[0][pathauto] | path[0][alias] | field_c_n_hide_sidebar[value] |
      | [TEST] Page Internal | 1      | 0                 | /internal-test | 0                             |
    And 'Footer' menu_links:
      | title                       | enabled | uri                 |
      | [TEST] External Footer link | 1       | https://example.com |
    And 'Primary Navigation' menu_links:
      | title                        | enabled | uri                 |
      | [TEST] External Primary link | 1       | https://example.com |
    And 'Secondary Navigation' menu_links:
      | title                          | enabled | uri                 |
      | [TEST] External Secondary link | 1       | https://example.com |

    When I visit civictheme_page "[TEST] Page Internal"
    And I should see the '.ct-secondary-navigation a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.ct-primary-navigation a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.ct-side-navigation a[href="https://example.com"]' element with the "target" attribute set to '_blank'

  @api
  Scenario: Mobile navigation blocks are provisioned.
    Given I am an anonymous user
    When I go to homepage
    Then I should see an ".ct-mobile-navigation__top-menu-wrapper" element
    Then I should see an ".ct-mobile-navigation__bottom-menu-wrapper" element
