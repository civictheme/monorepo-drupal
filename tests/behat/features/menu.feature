@civictheme @menu
Feature: Open external links in a new tab

  Ensure that external menu links open in a new tab.

  Background:
    Given "civictheme_page" content:
      | title                 | status | path[0][pathauto] | path[0][alias] | field_c_n_hide_sidebar[value] |
      | [TEST] Page Internal  | 1      | 0                 | /internal-test | 0                             |
    Given 'Footer' menu_links:
      | title                       | enabled | uri                     |
      | [TEST] External Footer link | 1       | https://example.com     |
    Given 'Primary Navigation' menu_links:
      | title                        | enabled | uri                     |
      | [TEST] External Primary link | 1       | https://example.com     |
    Given 'Secondary Navigation' menu_links:
      | title                          | enabled | uri                     |
      | [TEST] External Secondary link | 1       | https://example.com     |
    Given menus:
      | label         | description        | id            |
      | Test new menu | Text external link | test-new-menu |
    Given 'Test new menu' menu_links:
      | title                    | enabled | uri                     |
      | [TEST] External new link | 1       | https://example.com     |

  @api
  Scenario: External menu links open in a new tab.
    Given I am logged in as a user with the "Administrator" role
    When I go to "/admin/structure/block/add/menu_block:civictheme-footer/civictheme?region=footer_top_1"
    And I press "Save"
    When I go to "/admin/structure/block/add/menu_block:test-new-menu/civictheme?region=footer_top_1"
    And I press "Save"
    Given I visit civictheme_page "[TEST] Page Internal"
    And I should see the '.civictheme-secondary-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.civictheme-primary-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.civictheme-side-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
    And I should see the '.civictheme-navigation__menu a[href="https://example.com"]' element with the "target" attribute set to '_blank'
