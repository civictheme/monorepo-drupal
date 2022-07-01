@civictheme @civictheme_cards @civictheme_page
Feature: View of Page content type

  Ensure that Page content can be viewed correctly.

  Background:
    Given managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
      | test_svg.svg   | public://civictheme_test/test_svg.svg   | test_svg.svg   |

    And "civictheme_image" media:
      | name                    | field_c_m_image |
      | [TEST] CivicTheme Image | test_image.jpg  |

    And "civictheme_page" content:
      | title                             | status |
      | [TEST] Page Promo cards test      | 1      |
      | [TEST] Page Navigation cards test | 1      |
      | [TEST] Page Event cards test      | 1      |
      | [TEST] Page Subject cards test    | 1      |
      | [TEST] Page Service cards test    | 1      |
      | [TEST] Page Tasks cards test      | 1      |
      | [TEST] Page Reference cards test  | 1      |
      | [TEST] Page Revision test         | 1      |

    And "civictheme_event" content:
      | title                                  | status |
      | [TEST] Reference Page Event cards test | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with promo cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Promo cards test" has "civictheme_card_container" paragraph:
      | field_c_p_title        | [TEST] Promo card container                      |
      | field_c_p_column_count | 4                                                |
      | field_c_p_header_link  | 0: View all promo cards - 1: https://example.com |
      | field_c_p_fill_width   | 0                                                |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Promo card title                      |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 1                    |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 2                    |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Promo card container" has "civictheme_promo_card" paragraph:
      | field_c_p_date    | 2021-04-30                            |
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Promo card title 3                    |

    When I visit "civictheme_page" "[TEST] Page Promo cards test"
    And I should see the text "[TEST] Promo card container"
    Then I should see the link "View all promo cards" with "https://example.com" in 'div.civictheme-card-container'
    And I should see an "div.civictheme-promo-card" element
    And I should see the text "Promo card title"
    And I should see the text "Promo card title 1"
    And I should see the text "Promo card title 2"
    And I should see the text "Promo card title 3"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Navigation cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Navigation cards test" has "civictheme_card_container" paragraph:
      | field_c_p_title        | [TEST] Navigation card container                      |
      | field_c_p_column_count | 4                                                     |
      | field_c_p_header_link  | 0: View all navigation cards - 1: https://example.com |
      | field_c_p_fill_width   | 0                                                     |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Navigation card container" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text                          |
      | field_c_p_theme   | light                                 |
      | field_c_p_title   | Navigation card title                 |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Navigation card container" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 2                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 1               |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Navigation card container" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 2               |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Navigation card container" has "civictheme_navigation_card" paragraph:
      | field_c_p_image   | [TEST] CivicTheme Image               |
      | field_c_p_link    | 0: Test link - 1: https://example.com |
      | field_c_p_summary | Summary text 3                        |
      | field_c_p_theme   | dark                                  |
      | field_c_p_title   | Navigation card title 3               |

    When I visit "civictheme_page" "[TEST] Page Navigation cards test"
    And I should see the text "[TEST] Navigation card container"
    Then I should see the link "View all navigation cards" with "https://example.com" in 'div.civictheme-card-container'
    And I should see 4 "div.civictheme-navigation-card" elements
    And I should see 4 ".civictheme-navigation-card__title" elements
    And I should see 4 ".civictheme-navigation-card__summary" elements
    And I should see the text "Navigation card title"
    And I should see the text "Navigation card title 1"
    And I should see the text "Navigation card title 2"
    And I should see the text "Navigation card title 3"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Subject cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Subject cards test" has "civictheme_card_container" paragraph:
      | field_c_p_title        | [TEST] Subject card container                      |
      | field_c_p_column_count | 3                                                  |
      | field_c_p_header_link  | 0: View all Subject cards - 1: https://example.com |
      | field_c_p_fill_width   | 0                                                  |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Subject card container" has "civictheme_subject_card" paragraph:
      | field_c_p_image | [TEST] CivicTheme Image               |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | light                                 |
      | field_c_p_title | Subject card title                    |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Subject card container" has "civictheme_subject_card" paragraph:
      | field_c_p_image | [TEST] CivicTheme Image               |
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |
      | field_c_p_title | Subject card title 1                  |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Subject card container" has "civictheme_subject_card" paragraph:
      | field_c_p_link  | 0: Test link - 1: https://example.com |
      | field_c_p_theme | dark                                  |
      | field_c_p_title | Subject card title 2                  |

    When I visit "civictheme_page" "[TEST] Page Subject cards test"
    And I should see the text "[TEST] Subject card container"
    Then I should see the link "View all Subject cards" with "https://example.com" in 'div.civictheme-card-container'
    And I should see 1 "div.civictheme-card-container" elements
    And I should see 3 "div.civictheme-subject-card" elements
    And I should see 2 "div.civictheme-subject-card__image img" elements
    And I should see 3 "div.civictheme-subject-card__title" elements
    And I should not see an "div.civictheme-navigation-card" element
    And I should see the text "Subject card title"
    And I should see the text "Subject card title 1"
    And I should see the text "Subject card title 2"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Service cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Service cards test" has "civictheme_card_container" paragraph:
      | field_c_p_title        | [TEST] Service card container                      |
      | field_c_p_column_count | 3                                                  |
      | field_c_p_header_link  | 0: View all Service cards - 1: https://example.com |
      | field_c_p_fill_width   | 0                                                  |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Service card container" has "civictheme_service_card" paragraph:
      | field_c_p_links | 0: Test link 1 - 1: https://example.com, 0: Test link 11 - 1: https://example.com |
      | field_c_p_theme | light                                                                             |
      | field_c_p_title | Service card title                                                                |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Service card container" has "civictheme_service_card" paragraph:
      | field_c_p_links | 0: Test link 2 - 1: https://example.com, 0: Test link 21 - 1: https://example.com |
      | field_c_p_theme | dark                                                                              |
      | field_c_p_title | Service card title 1                                                              |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Service card container" has "civictheme_service_card" paragraph:
      | field_c_p_links | 0: Test link 3 - 1: https://example.com, 0: Test link 31 - 1: https://example.com, 0: Test link 32 - 1: https://example.com, 0: Test link 33 - 1: https://example.com |
      | field_c_p_theme | dark                                                                                                                                                                  |
      | field_c_p_title | Service card title 2                                                                                                                                                  |

    When I visit "civictheme_page" "[TEST] Page Service cards test"
    And I should see the text "[TEST] Service card container"
    Then I should see the link "View all Service cards" with "https://example.com" in 'div.civictheme-card-container'
    And I should see 1 "div.civictheme-card-container" elements
    And I should see 3 "div.civictheme-service-card" elements
    And I should see 2 "div.civictheme-service-card.civictheme-theme-dark" elements
    And I should see 1 "div.civictheme-service-card.civictheme-theme-light" elements
    And I should see 3 "div.civictheme-service-card__title" elements
    And I should see 3 "ul.civictheme-service-card__links" elements
    And I should not see an "div.civictheme-subject-card" element
    And I should not see an "div.civictheme-service-card img" element
    And I should see the text "Service card title"
    And I should see the text "Service card title 1"
    And I should see the text "Service card title 2"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Tasks cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Tasks cards test" has "civictheme_card_container" paragraph:
      | field_c_p_title        | [TEST] Tasks cards container |
      | field_c_p_column_count | 3                            |
      | field_c_p_fill_width   | 0                            |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Tasks cards container" has "civictheme_task_card" paragraph:
      | field_c_p_link    | 0: '' - 1: https://example.com/card1 |
      | field_c_p_title   | Task card title 1                    |
      | field_c_p_summary | Summary text 1                       |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Tasks cards container" has "civictheme_task_card" paragraph:
      | field_c_p_link    | 0: '' - 1: https://example.com/card2                                                              |
      | field_c_p_title   | Task card title 2                                                                                 |
      | field_c_p_summary | Quisque velit nisi, pretium ut lacinia in, elementum id enim. Nulla porttitor accumsan tincidunt. |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Tasks cards container" has "civictheme_task_card" paragraph:
      | field_c_p_link    | 0: '' - 1: https://example.com/card3 |
      | field_c_p_title   | Task card title 3                    |
      | field_c_p_summary | Summary text 3                       |

    When I visit "civictheme_page" "[TEST] Page Tasks cards test"
    And I should see the text "[TEST] Tasks cards container"
    And I should not see an "div.civictheme-card-container__link a" element
    And I should see 1 "div.civictheme-card-container" elements
    And I should see 3 "div.civictheme-navigation-card--small" elements
    And I should see 3 "div.civictheme-navigation-card__content" elements
    And I should see 3 "div.civictheme-navigation-card__title" elements
    And I should see 3 "div.civictheme-navigation-card__summary" elements
    And I should not see an "div.civictheme-subject-card" element
    Then I should see the link "Task card title 1" with "https://example.com/card1" in 'div.civictheme-navigation-card__title'
    Then I should see the link "Task card title 1" with "https://example.com/card1" in 'div.civictheme-navigation-card__title'
    Then I should see the link "Task card title 1" with "https://example.com/card1" in 'div.civictheme-navigation-card__title'
    And save screenshot

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with Reference cards
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page Reference cards test" has "civictheme_card_container" paragraph:
      | field_c_p_title        | [TEST] Reference cards container |
      | field_c_p_column_count | 3                                |
      | field_c_p_fill_width   | 0                                |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_event_card_ref" paragraph:
      | field_c_p_reference | [TEST] Reference Page Event cards test |
      | field_c_p_theme     | light                                  |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_subject_card_ref" paragraph:
      | field_c_p_reference | [TEST] Page Promo cards test |
      | field_c_p_theme     | light                        |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_navigation_card_ref" paragraph:
      | field_c_p_reference | [TEST] Page Promo cards test |
      | field_c_p_theme     | dark                         |
    And "field_c_p_cards" in "civictheme_card_container" "paragraph" with "field_c_p_title" of "[TEST] Reference cards container" has "civictheme_promo_card_ref" paragraph:
      | field_c_p_reference | [TEST] Page Promo cards test |
      | field_c_p_theme     | light                        |

    When I visit "civictheme_page" "[TEST] Page Reference cards test"
    And I should see the text "[TEST] Reference cards container"
    And I should not see an "div.civictheme-card-container__link a" element
    And I should see 1 "div.civictheme-card-container" elements
    And I should see 1 "div.civictheme-event-card__content" elements
    And I should see 1 "div.civictheme-subject-card__title" elements
    And I should see 1 "div.civictheme-navigation-card__title" elements
    And I should see 1 "div.civictheme-promo-card__content" elements
    And I should see 3 "div.civictheme-card-container__card .civictheme-theme-light" elements
    And I should see 1 "div.civictheme-card-container__card .civictheme-theme-dark" elements
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
    And I should see an "aside.civictheme-content__sidebar" element
    When I visit "civictheme_page" "[TEST] Page without sidebar"
    And I should see the text "[TEST] Page without sidebar"
    And I should not see an "aside.civictheme-content__sidebar" element

  @api @breadcrumb
  Scenario: CivicTheme page content type page breadcrumb theme can be overridden
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                             | status | field_c_n_banner_theme |
      | [TEST] Page breadcrumb light      | 1      | light                  |
      | [TEST] Page breadcrumb dark       | 1      | dark                   |

    When I visit "civictheme_page" "[TEST] Page breadcrumb light"
    And I should see the text "[TEST] Page breadcrumb light"
    And I should see an "nav.civictheme-breadcrumb.civictheme-theme-light" element
    And I should not see an "nav.civictheme-breadcrumb.civictheme-theme-dark" element
    When I visit "civictheme_page" "[TEST] Page breadcrumb dark"
    And I should see the text "[TEST] Page breadcrumb dark"
    And I should see an "nav.civictheme-breadcrumb.civictheme-theme-dark" element
    And I should not see an "nav.civictheme-breadcrumb.civictheme-theme-light" element

  @api @lastupdated
  Scenario: CivicTheme page content type page can configure Last updated date display
    Given I am an anonymous user
    And "civictheme_page" content:
      | title                    | status | field_c_n_show_last_updated |
      | [TEST] Page with date    | 1      | 1                           |
      | [TEST] Page without date | 1      | 0                           |

    When I visit "civictheme_page" "[TEST] Page with date"
    And I should see the text "[TEST] Page with date"
    And I should see an "div.civictheme-banner__content-middle" element
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
    And I should see an "div.civictheme-banner__breadcrumb" element
    When I visit "civictheme_page" "[TEST] Page without breadcrumb"
    And I should not see an "div.civictheme-banner__breadcrumb" element
