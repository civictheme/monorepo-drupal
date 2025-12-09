@p0 @civictheme @navigation
Feature: Navigation

  Background:
    Given no 'Footer' menu_links:
      | title                       | enabled | uri                 |
      | [TEST] External Footer link | 1       | https://example.com |
      | <script id="xss-menu-link--footer">alert('XSS Footer Menu Link');</script> | 1       | https://example.com |
      | <script id="xss-menu-link--primary-navigation">alert('XSS Primary Navigation Menu Link');</script> | 1       | https://example.com |
      | <script id="xss-menu-link--secondary-navigation">alert('XSS Secondary Navigation Menu Link');</script> | 1       | https://example.com |


    Given no 'Primary Navigation' menu_links:
      | title                        | enabled | uri                 |
      | [TEST] External Primary link | 1       | https://example.com |
    Given no 'Secondary Navigation' menu_links:
      | title                          | enabled | uri                 |
      | [TEST] External Secondary link | 1       | https://example.com |

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

  @api @security
  Scenario:XSS - Menu links
    Given "civictheme_page" content:
      | title                | status | path[0][pathauto] | path[0][alias] | field_c_n_hide_sidebar[value] |
      | [TEST] XSS Test Page | 1      | 0                 | /internal-test | 0                             |
    And 'Footer' menu_links:
      | title                       | enabled | uri                 |
      | <script id="xss-menu-link--footer">alert('XSS Footer Menu Link');</script> | 1       | internal:/internal-test |
    And 'Primary Navigation' menu_links:
      | title                        | enabled | uri                 |
      | <script id="xss-menu-link--primary-navigation">alert('XSS Primary Navigation Menu Link');</script> | 1       | internal:/internal-test |
    And 'Secondary Navigation' menu_links:
      | title                          | enabled | uri                 |
      | <script id="xss-menu-link--secondary-navigation">alert('XSS Secondary Navigation Menu Link');</script> | 1       | internal:/internal-test |

    When I visit "civictheme_page" "[TEST] XSS Test Page"
    And I should not see an "script#xss-menu-link--primary-navigation" element
    And I should see the text "alert('XSS Primary Navigation Menu Link')"
    And I should not see an "script#xss-menu-link--secondary-navigation" element
    And I should see the text "alert('XSS Secondary Navigation Menu Link')"
