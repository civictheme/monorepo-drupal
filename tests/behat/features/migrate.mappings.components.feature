@p0 @civictheme @civictheme_migrate @civictheme_migrate_components
Feature: CivicTheme migrate module Component mapping

  Background:
    Given no civictheme_page content:
      | title                           |
      | [TEST] Migrated Page Content 31 |
      | [TEST] Migrated Page Content 32 |

    And no managed files:
      | filename                                                 |
      | test_civictheme_migrate.node_civictheme_page_3.json      |
      | test_civictheme_migrate.media_civictheme_image_1.json    |
      | test_civictheme_migrate.media_civictheme_image_2.json    |
      | test_civictheme_migrate.media_civictheme_document_1.json |

    And no topic terms:
      | name                          |
      | [TEST] Migrated event topic 1 |
      | [TEST] Migrated topic 1       |
      | [TEST] Migrated topic 2       |
      | [TEST] Migrated topic 3       |
      | [TEST] Migrated topic 4       |

    # Files used as migration sources and are attached to the migrations.
    And managed file:
      | path                                                        | uri                                                               |
      | migrate/civictheme_migrate.node_civictheme_page_2.json      | public://test_civictheme_migrate.node_civictheme_page_2.json      |
      | migrate/civictheme_migrate.media_civictheme_image_1.json    | public://test_civictheme_migrate.media_civictheme_image_1.json    |
      | migrate/civictheme_migrate.media_civictheme_image_2.json    | public://test_civictheme_migrate.media_civictheme_image_2.json    |
      | migrate/civictheme_migrate.media_civictheme_document_1.json | public://test_civictheme_migrate.media_civictheme_document_1.json |

    # Files used as migration assets and are served from the local server as from remote.
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_1.json
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_image_2.json
    # @see fixtures/migrate/civictheme_migrate.media_civictheme_document_1.json
    And managed file:
      | path               | uri                          |
      | migrate/dummy1.jpg | public://migrated_dummy1.jpg |
      | migrate/dummy2.jpg | public://migrated_dummy2.jpg |
      | migrate/dummy3.jpg | public://migrated_dummy3.jpg |
      | migrate/dummy4.jpg | public://migrated_dummy4.jpg |
      | migrate/dummy1.pdf | public://migrated_dummy1.pdf |
      | migrate/dummy2.pdf | public://migrated_dummy2.pdf |
      | migrate/dummy3.pdf | public://migrated_dummy3.pdf |
      | migrate/dummy4.pdf | public://migrated_dummy4.pdf |
      | migrate/dummy1.txt | public://migrated_dummy1.txt |
      | migrate/dummy2.txt | public://migrated_dummy2.txt |
      | migrate/dummy3.txt | public://migrated_dummy3.txt |
      | migrate/dummy4.txt | public://migrated_dummy4.txt |

    # Fully reset migration runs and migration configs.
    And I clear "media_civictheme_image" migration map
    And I clear "media_civictheme_document" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"

  @api @drush
  Scenario: Migration local sources for components can be updated from the migration edit form
    Given I am logged in as a user with the "administrator" role

    # Attaching 2 source image files as the node migration depends on images in both files.
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_1.json" to "files[source_update_files][]"
    And I press "Update Migration"
    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_image/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_image_2.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/media_civictheme_document/edit"
    And I attach the file "migrate/civictheme_migrate.media_civictheme_document_1.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I go to "admin/structure/migrate/manage/civictheme_migrate/migrations/node_civictheme_page/edit"
    And I attach the file "migrate/civictheme_migrate.node_civictheme_page_3.json" to "files[source_update_files][]"
    And I press "Update Migration"

    When I run drush "mim --group=civictheme_migrate"
    And I visit "civictheme_page" "[TEST] Migrated Page Content 31"

    # Asserting field mappings.
    # Alias.
    Then I should be in the "/migrated/page-content-31" path
    # Banner.
    Then I should see "[TEST] Banner title - Migrated Page Content 31" in the ".ct-banner__title" element
    # Content.
    And I should see an ".ct-basic-content" element
    And I should see the text "[TEST] Basic text content"
    # Manual list.
    And I should see an ".ct-list" element
    # Promo card.
    And I should see 2 ".ct-promo-card" elements
    And I should see the text "[TEST] Promo card 1"
    And I should see "[TEST] Promo card summary" in the ".ct-promo-card__content" element
    # Promo card with image.
    And I should see the text "[TEST] Promo card with image"
    And the response should contain "dummy1.jpg"
    # Event card.
    And I should see "[TEST] Event card 1" in the ".ct-event-card" element
    And I should see "[TEST] Event card summary" in the ".ct-event-card__content" element
    And I should see "[TEST] Migrated event topic 1" in the ".ct-event-card__tags" element
    And I should see "[TEST] Migrated topic 2" in the ".ct-event-card__tags" element
    # Publication card.
    And I should see "[TEST] Publication card 1" in the ".ct-publication-card" element
    And I should see "[TEST] Publication card summary" in the ".ct-publication-card__summary" element
    And the response should contain "dummy1.pdf"
    # Service card.
    And I should see "[TEST] Service card 1" in the ".ct-service-card" element
    And I should see 2 ".ct-service-card .ct-link" elements
    And I should see "[TEST] Service card 1 link 1" in the ".ct-service-card" element
    And I should see "[TEST] Service card 1 link 2" in the ".ct-service-card" element
    # Subject card.
    And I should see "[TEST] Subject card 1" in the ".ct-subject-card" element
    And the response should contain "Dummy file 2"
    # Accordion.
    And I should see an ".ct-accordion" element
    And I should see an ".ct-accordion__content" element
    And I should see an ".ct-accordion__panels" element
    And I should see 5 ".ct-accordion__panels__panel" elements
    And I should see the text "[TEST] Accordion panel 1"
    And I should see the text "[TEST] Accordion panel content 1"
    And I should see the text "[TEST] Accordion panel 2"
    And I should see the text "[TEST] Accordion panel content 2"
    And I should see the text "[TEST] Accordion panel 3"
    And I should see the text "[TEST] Accordion panel content 3"
    And I should see the text "[TEST] Accordion panel 4"
    And I should see the text "[TEST] Accordion panel content 4"
    And I should see the text "[TEST] Accordion panel 5"
    And I should see the text "[TEST] Accordion panel content 5"

    And I visit "civictheme_page" "[TEST] Migrated Page Content 32"
    # Alias.
    Then I should be in the "/migrated/page-content-32" path
    Then I should see "[TEST] Banner title - Migrated Page Content 32" in the ".ct-banner__title" element

    # Content.
    And I should see an ".ct-basic-content" element
    And I should see the text "[TEST] Basic text content"

    # Manual list.
    And I should see 1 ".ct-list" elements
    And I should see 5 ".ct-item-grid__item " elements
    And I should see "[TEST] Promo card" in the ".ct-promo-card" element
    And I should see "[TEST] Event card" in the ".ct-event-card" element
    And I should see "[TEST] Service card" in the ".ct-service-card" element
    And I should see "[TEST] Subject card" in the ".ct-subject-card" element

    # Accordion.
    And I should see an ".ct-accordion" element
    And I should see an ".ct-accordion__content" element
    And I should see an ".ct-accordion__panels" element
    And I should see 4 ".ct-accordion__panels__panel" elements
    And I should see the text "[TEST] Accordion panel 1"
    And I should see the text "[TEST] Accordion panel content 1"
    And I should see the text "[TEST] Accordion panel 2"
    And I should see the text "[TEST] Accordion panel content 2"
    And I should see the text "[TEST] Accordion panel 3"
    And I should see the text "[TEST] Accordion panel content 3"
    And I should see the text "[TEST] Accordion panel 4"
    And I should see the text "[TEST] Accordion panel content 4"

    # Reset migration and configs.
    And I run drush "mr --group=civictheme_migrate"
    And I clear "media_civictheme_image" migration map
    And I clear "media_civictheme_document" migration map
    And I clear "node_civictheme_page" migration map
    And I run drush "config-set migrate_plus.migration.media_civictheme_image source.urls []"
    And I run drush "config-set migrate_plus.migration.media_civictheme_document source.urls []"
    And I run drush "config-set migrate_plus.migration.node_civictheme_page source.urls []"
    And no civictheme_page content:
      | title                           |
      | [TEST] Migrated Page Content 31 |
      | [TEST] Migrated Page Content 32 |
