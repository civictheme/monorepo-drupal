@civictheme @civictheme_cards @civictheme_page
Feature: View of Page content type

  Ensure that Page content can be viewed correctly.

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |
    Given "civictheme_site_sections" terms:
      | name                  |
      | [TEST] Site Section 1 |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                             | status | field_c_n_site_section |
      | [TEST] Page Promo cards test      | 1      |                        |
      | [TEST] Page Navigation cards test | 1      |                        |
      | [TEST] Page Event cards test      | 1      |                        |
      | [TEST] Page Subject cards test    | 1      |                        |
      | [TEST] Page Service cards test    | 1      |                        |
      | [TEST] Page Tasks cards test      | 1      |                        |
      | [TEST] Page Reference cards test  | 1      |                        |
      | [TEST] Page Revision test         | 1      |                        |
      | [TEST] Page with Site section     | 1      | [TEST] Site Section 1  |

    And "civictheme_event" content:
      | title                                  | status |
      | [TEST] Reference Page Event cards test | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with promo cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Promo manual list                         |
      | field_c_p_column_count    | 4                                                |
      | field_c_p_list_link_above | 0: View all promo cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Promo card title                      |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 1                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 2                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Promo manual list" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 3                    |

    When I visit "civictheme_page" "[TEST] Page Promo cards test"
    And I should see the text "[TEST] Promo manual list"
    Then I should see the link "View all promo cards" with "https://example.com" in 'div.ct-list'
    And I should see an "div.ct-promo-card" element
    And I should see the text "Promo card title"
    And I should see the text "Promo card title 1"
    And I should see the text "Promo card title 2"
    And I should see the text "Promo card title 3"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Navigation cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Navigation cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Navigation manual list                         |
      | field_c_p_column_count    | 4                                                     |
      | field_c_p_list_link_above | 0: View all navigation cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                     |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Navigation card title                 |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 1               |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 2               |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Navigation manual list" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 3               |

    When I visit "civictheme_page" "[TEST] Page Navigation cards test"
    And I should see the text "[TEST] Navigation manual list"
    Then I should see the link "View all navigation cards" with "https://example.com" in 'div.ct-list'
    And I should see 4 "div.ct-navigation-card" elements
    And I should see 4 ".ct-navigation-card__title" elements
    And I should see 4 ".ct-navigation-card__summary" elements
    And I should see the text "Navigation card title"
    And I should see the text "Navigation card title 1"
    And I should see the text "Navigation card title 2"
    And I should see the text "Navigation card title 3"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Subject cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Subject cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Subject manual list                         |
      | field_c_p_column_count    | 3                                                  |
      | field_c_p_list_link_above | 0: View all Subject cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Subject manual list" has "civictheme_subject_card" paragraph:
      | field_c_p_image | [TEST] CivicTheme Image               |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | light                                 |
      | field_c_p_title | Subject card title                    |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Subject manual list" has "civictheme_subject_card" paragraph:
      | field_c_p_image | [TEST] CivicTheme Image               |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |
      | field_c_p_title | Subject card title 1                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Subject manual list" has "civictheme_subject_card" paragraph:
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |
      | field_c_p_title | Subject card title 2                  |

    When I visit "civictheme_page" "[TEST] Page Subject cards test"
    And I should see the text "[TEST] Subject manual list"
    Then I should see the link "View all Subject cards" with "https://example.com" in 'div.ct-list'
    And I should see 1 "div.ct-list" elements
    And I should see 3 "div.ct-subject-card" elements
    And I should see 2 "div.ct-subject-card__image img" elements
    And I should see 3 "div.ct-subject-card__title" elements
    And I should not see an "div.ct-navigation-card" element
    And I should see the text "Subject card title"
    And I should see the text "Subject card title 1"
    And I should see the text "Subject card title 2"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Service cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Service cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title           | [TEST] Service manual list                         |
      | field_c_p_column_count    | 3                                                  |
      | field_c_p_list_link_above | 0: View all Service cards - 1: https://example.com |
      | field_c_p_fill_width      | 0                                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Service manual list" has "civictheme_service_card" paragraph:
      | field_c_p_links | 0: Test link 1 - 1: https://example.com, 0: Test link 11 - 1: https://example.com |
      | field_c_p_theme | light                                                                             |
      | field_c_p_title | Service card title                                                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Service manual list" has "civictheme_service_card" paragraph:
      | field_c_p_links | 0: Test link 2 - 1: https://example.com, 0: Test link 21 - 1: https://example.com |
      | field_c_p_theme | dark                                                                              |
      | field_c_p_title | Service card title 1                                                              |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Service manual list" has "civictheme_service_card" paragraph:
      | field_c_p_links | 0: Test link 3 - 1: https://example.com, 0: Test link 31 - 1: https://example.com, 0: Test link 32 - 1: https://example.com, 0: Test link 33 - 1: https://example.com |
      | field_c_p_theme | dark                                                                                                                                                                  |
      | field_c_p_title | Service card title 2                                                                                                                                                  |

    When I visit "civictheme_page" "[TEST] Page Service cards test"
    And I should see the text "[TEST] Service manual list"
    Then I should see the link "View all Service cards" with "https://example.com" in 'div.ct-list'
    And I should see 1 "div.ct-list" elements
    And I should see 3 "div.ct-service-card" elements
    And I should see 2 "div.ct-service-card.ct-theme-dark" elements
    And I should see 1 "div.ct-service-card.ct-theme-light" elements
    And I should see 3 "div.ct-service-card__title" elements
    And I should see 3 "ul.ct-service-card__links" elements
    And I should not see an "div.ct-subject-card" element
    And I should not see an "div.ct-service-card img" element
    And I should see the text "Service card title"
    And I should see the text "Service card title 1"
    And I should see the text "Service card title 2"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Tasks cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Tasks cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title        | [TEST] Tasks cards container |
      | field_c_p_column_count | 3                            |
      | field_c_p_fill_width   | 0                            |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Tasks cards container" has "civictheme_task_card" paragraph:
      | field_c_p_link    | 0: '' - 1: https://example.com/card1 |
      | field_c_p_title   | Task card title 1                    |
      | field_c_p_summary | Summary text 1                       |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Tasks cards container" has "civictheme_task_card" paragraph:
      | field_c_p_link    | 0: '' - 1: https://example.com/card2                                                              |
      | field_c_p_title   | Task card title 2                                                                                 |
      | field_c_p_summary | Quisque velit nisi, pretium ut lacinia in, elementum id enim. Nulla porttitor accumsan tincidunt. |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Tasks cards container" has "civictheme_task_card" paragraph:
      | field_c_p_link    | 0: '' - 1: https://example.com/card3 |
      | field_c_p_title   | Task card title 3                    |
      | field_c_p_summary | Summary text 3                       |

    When I visit "civictheme_page" "[TEST] Page Tasks cards test"
    And I should see the text "[TEST] Tasks cards container"
    And I should not see an "div.ct-list__link a" element
    And I should see 1 "div.ct-list" elements
    And I should see 3 "div.ct-navigation-card--small" elements
    And I should see 3 "div.ct-navigation-card__content" elements
    And I should see 3 "div.ct-navigation-card__title" elements
    And I should see 3 "div.ct-navigation-card__summary" elements
    And I should not see an "div.ct-subject-card" element
    Then I should see the link "Task card title 1" with "https://example.com/card1" in 'div.ct-navigation-card__title'
    Then I should see the link "Task card title 1" with "https://example.com/card1" in 'div.ct-navigation-card__title'
    Then I should see the link "Task card title 1" with "https://example.com/card1" in 'div.ct-navigation-card__title'
    And save screenshot

  @api @abc
  Scenario: CivicTheme page content type page can be viewed by anonymous with Reference cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Reference cards test" has "civictheme_manual_list" paragraph:
      | field_c_p_title        | [TEST] Reference cards container |
      | field_c_p_column_count | 3                                |
      | field_c_p_fill_width   | 0                                |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_event_card_ref" paragraph:
      | field_c_p_reference | [TEST] Reference Page Event cards test |
      | field_c_p_theme     | light                                  |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Page Promo cards test |
      | field_c_p_theme     | light                        |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Page Promo cards test |
      | field_c_p_theme     | dark                         |
    And "field_c_p_list_items" in "civictheme_manual_list" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_promo_card_ref" paragraph:
      | field_c_p_reference | [TEST] Page Promo cards test |
      | field_c_p_theme     | light                        |

    When I visit "civictheme_page" "[TEST] Page Reference cards test"
    And I should see the text "[TEST] Reference cards container"
    And I should not see an "div.ct-list__link a" element
    And I should see 1 "div.ct-list" elements
    And I should see 1 "div.ct-event-card__content" elements
    And I should see 1 "div.ct-subject-card__title" elements
    And I should see 1 "div.ct-navigation-card__title" elements
    And I should see 1 "div.ct-promo-card__content" elements
    And I should see 3 "div.ct-item-grid__item > .ct-theme-light" elements
    And I should see 1 "div.ct-item-grid__item > .ct-theme-dark" elements
    And save screenshot

  @api @javascript @smoke
  Scenario: CivicTheme page revisions can be viewed without error

    Given I am logged in as a user with the "Site Administrator" role
    When I edit "civictheme_page" "[TEST] Page Revision test"
    And I fill in "Title" with "[TEST] Page New Revision test"
    And I press "Save"
    And I click "Revisions"
    And I click on ".node-revision-table .even a" element
    And I should see "Revision of [TEST] Page Revision test"
    And save screenshot

  @api @sidebar
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

  @api @breadcrumb
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

  @api @lastcustomupdated
  Scenario: CivicTheme page content type page can configure Last updated date display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                                      | status | field_c_n_show_last_updated | field_c_n_custom_last_updated |
      | [TEST] Page with date                      | 1      | 1                           | 2022-07-01                    |
      | [TEST] Page with last updated date checked | 1      | 1                           |                               |
      | [TEST] Page without date                   | 1      | 0                           | 2022-07-14                    |

    When I visit "civictheme_page" "[TEST] Page with date"
    And I should see the text "[TEST] Page with date"
    And I should see an "div.ct-banner__content-middle" element
    And I should see the text "Last updated: 1 Jul 2022"
    When I visit "civictheme_page" "[TEST] Page with last updated date checked"
    And I should see the text "[TEST] Page with last updated date checked"
    And I should see an "div.ct-banner__content-middle" element
    And I should see the text "Last updated"
    When I visit "civictheme_page" "[TEST] Page without date"
    And I should see the text "[TEST] Page without date"
    And I should not see the text "Last updated"

  @api @lastupdated
  Scenario: CivicTheme page content type page can configure Last updated date display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                    | status | field_c_n_show_last_updated |
      | [TEST] Page with date    | 1      | 1                           |
      | [TEST] Page without date | 1      | 0                           |

    When I visit "civictheme_page" "[TEST] Page with date"
    And I should see the text "[TEST] Page with date"
    And I should see an "div.ct-banner__content-middle" element
    And I should see the text "Last updated"
    When I visit "civictheme_page" "[TEST] Page without date"
    And I should see the text "[TEST] Page without date"
    And I should not see the text "Last updated"

  @api @breadcrumb
  Scenario: CivicTheme page content type page can configure breadcrumb display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                          | status | field_c_n_banner_hide_breadcrumb |
      | [TEST] Page with breadcrumb    | 1      | 0                                |
      | [TEST] Page without breadcrumb | 1      | 1                                |

    When I visit "civictheme_page" "[TEST] Page with breadcrumb"
    And I should see an "div.ct-banner__breadcrumb" element
    When I visit "civictheme_page" "[TEST] Page without breadcrumb"
    And I should not see an "div.ct-banner__breadcrumb" element

  @api @banner_title
  Scenario: CivicTheme page content type page can override banner title.
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                            | status | field_c_n_banner_title  |
      | [TEST] Page with Banner title    | 1      | [OVERRIDE] Banner title |
      | [TEST] Page without Banner title | 1      |                         |

    When I visit "civictheme_page" "[TEST] Page with Banner title"
    Then I should not see "[TEST] Page with Banner title" in the "div.ct-banner__title" element
    And I should see "[OVERRIDE] Banner title" in the "div.ct-banner__title" element
    When I visit "civictheme_page" "[TEST] Page without Banner title"
    Then I should see "[TEST] Page without Banner title" in the "div.ct-banner__title" element

  @api @sitesections
  Scenario: CivicTheme page content type page can configure Site sections
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                            | status | field_c_n_site_section |
      | [TEST] Page with Site section    | 1      | [TEST] Site Section 1  |
      | [TEST] Page without Site section | 1      |                        |

    When I visit "civictheme_page" "[TEST] Page with Site section"
    And I should see the text "[TEST] Page with Site section"
    And I should see an "body.ct-site-section--test-site-section-1" element
    And I should see an "div.ct-banner__section" element
    And I should see the text "[TEST] Site Section 1"
    When I visit "civictheme_page" "[TEST] Page without Site section"
    And I should see the text "[TEST] Page without Site section"
    And I should not see an "div.ct-banner__section" element
