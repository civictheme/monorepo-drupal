@p1 @civictheme @content_type @civictheme_page
Feature: CivicTheme Page content type render

  Ensure that Page content can be viewed correctly.

  @api @javascript
  Scenario: CivicTheme page revisions can be viewed without error
    Given I am logged in as a user with the "Site Administrator" role
    And "civictheme_page" content:
      | title                     | status | field_c_n_site_section |
      | [TEST] Page Revision test | 1      |                        |
    When I edit "civictheme_page" "[TEST] Page Revision test"
    And I fill in "Title" with "[TEST] Page New Revision test"
    And I press "Save"
    And I click "Revisions"
    And I click on ".node-revision-table .even a" element
    And I should see "Revision of [TEST] Page Revision test"

  @api
  Scenario: CivicTheme page content type page can configure sidebar display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                       | status | field_c_n_hide_sidebar |
      | [TEST] Page with sidebar    | 1      | 0                      |
      | [TEST] Page without sidebar | 1      | 1                      |

    When I visit "civictheme_page" "[TEST] Page with sidebar"
    And I should see the text "[TEST] Page with sidebar"
    And I should see an "aside.ct-layout__sidebar" element
    When I visit "civictheme_page" "[TEST] Page without sidebar"
    And I should see the text "[TEST] Page without sidebar"
    And I should not see an "aside.ct-layout__sidebar" element

  @api
  Scenario: CivicTheme page content type page can configure tags display
    Given I am an anonymous user
    And "civictheme_topics" terms:
      | name           |
      | [TEST] Topic 1 |
      | [TEST] Topic 2 |

    And "civictheme_page" content:
      | title                    | status | field_c_n_hide_tags | field_c_n_topics               |
      | [TEST] Page with tags    | 1      | 0                   | [TEST] Topic 1, [TEST] Topic 2 |
      | [TEST] Page without tags | 1      | 1                   | [TEST] Topic 2, [TEST] Topic 2 |

    When I visit "civictheme_page" "[TEST] Page with tags"
    And I should see the text "[TEST] Page with tags"
    And I should see an ".ct-tag-list span.ct-tag" element
    And I should see the text "[TEST] Topic 1"
    And I should see the text "[TEST] Topic 2"
    When I visit "civictheme_page" "[TEST] Page without tags"
    And I should see the text "[TEST] Page without tags"
    And I should not see an ".ct-tag-list span.ct-tag" element
    And I should not see the text "[TEST] Topic 1"
    And I should not see the text "[TEST] Topic 2"

  @api
  Scenario: CivicTheme page content type page breadcrumb theme can be overridden
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                        | status | field_c_n_banner_theme |
      | [TEST] Page breadcrumb light | 1      | light                  |
      | [TEST] Page breadcrumb dark  | 1      | dark                   |

    When I visit "civictheme_page" "[TEST] Page breadcrumb light"
    And I should see the text "[TEST] Page breadcrumb light"
    And I should see an "nav.ct-breadcrumb.ct-theme-light" element
    And I should not see an "nav.ct-breadcrumb.ct-theme-dark" element
    When I visit "civictheme_page" "[TEST] Page breadcrumb dark"
    And I should see the text "[TEST] Page breadcrumb dark"
    And I should see an "nav.ct-breadcrumb.ct-theme-dark" element
    And I should not see an "nav.ct-breadcrumb.ct-theme-light" element

  @api
  Scenario: CivicTheme page content type page can configure Last updated date display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                                      | status | field_c_n_show_last_updated | field_c_n_custom_last_updated |
      | [TEST] Page with date                      | 1      | 1                           | 2022-07-01                    |
      | [TEST] Page with last updated date checked | 1      | 1                           |                               |
      | [TEST] Page without date                   | 1      | 0                           | 2022-07-14                    |

    When I visit "civictheme_page" "[TEST] Page with date"
    And I should see the text "[TEST] Page with date"
    And I should see an ".ct-banner__content-middle" element
    And I should see the text "Last updated: 1 Jul 2022"
    When I visit "civictheme_page" "[TEST] Page with last updated date checked"
    And I should see the text "[TEST] Page with last updated date checked"
    And I should see an ".ct-banner__content-middle" element
    And I should see the text "Last updated"
    When I visit "civictheme_page" "[TEST] Page without date"
    And I should see the text "[TEST] Page without date"
    And I should not see the text "Last updated"

  @api
  Scenario: CivicTheme page content type page can configure Last updated date display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                    | status | field_c_n_show_last_updated |
      | [TEST] Page with date    | 1      | 1                           |
      | [TEST] Page without date | 1      | 0                           |

    When I visit "civictheme_page" "[TEST] Page with date"
    And I should see the text "[TEST] Page with date"
    And I should see an ".ct-banner__content-middle" element
    And I should see the text "Last updated"
    When I visit "civictheme_page" "[TEST] Page without date"
    And I should see the text "[TEST] Page without date"
    And I should not see the text "Last updated"

  @api
  Scenario: CivicTheme page content type page can configure breadcrumb display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                          | status | field_c_n_banner_hide_breadcrumb |
      | [TEST] Page with breadcrumb    | 1      | 0                                |
      | [TEST] Page without breadcrumb | 1      | 1                                |

    When I visit "civictheme_page" "[TEST] Page with breadcrumb"
    And I should see an ".ct-banner__breadcrumb" element
    When I visit "civictheme_page" "[TEST] Page without breadcrumb"
    And I should not see an ".ct-banner__breadcrumb" element

  @api
  Scenario: CivicTheme page content type page can override banner title.
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                            | status | field_c_n_banner_title  |
      | [TEST] Page with Banner title    | 1      | [OVERRIDE] Banner title |
      | [TEST] Page without Banner title | 1      |                         |

    When I visit "civictheme_page" "[TEST] Page with Banner title"
    Then I should not see "[TEST] Page with Banner title" in the ".ct-banner__title" element
    And I should see "[OVERRIDE] Banner title" in the ".ct-banner__title" element
    When I visit "civictheme_page" "[TEST] Page without Banner title"
    Then I should see "[TEST] Page without Banner title" in the ".ct-banner__title" element

  @api
  Scenario: CivicTheme page content type page can configure Site sections
    Given "civictheme_site_sections" terms:
      | name                  |
      | [TEST] Site Section 1 |
    And "civictheme_page" content:
      | title                            | status | field_c_n_site_section |
      | [TEST] Page with Site section    | 1      | [TEST] Site Section 1  |
      | [TEST] Page without Site section | 1      |                        |
    And I am an anonymous user
    When I visit "civictheme_page" "[TEST] Page with Site section"
    And I should see the text "[TEST] Page with Site section"
    And I should see an "body.ct-site-section---test-site-section-1" element
    And I should see an ".ct-banner__site-section" element
    And I should see the text "[TEST] Site Section 1"
    When I visit "civictheme_page" "[TEST] Page without Site section"
    And I should see the text "[TEST] Page without Site section"
    And I should not see an ".ct-banner__site-section" element
