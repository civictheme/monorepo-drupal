@paragraph @civic_service_card
Feature: Tests the Service card paragraph

  Ensure that Service card paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Service card" in the "civic_service_card" row

  @api
  Scenario: Service card paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_service_card/fields"
    And I should see the text "field_p_theme" in the "Theme" row
    And I should see the text "field_p_title" in the "Title" row
    And I should see the text "field_p_links" in the "Links" row

  @api
  Scenario: Card container paragraph field_p_cards fields settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields/paragraph.civic_card_container.field_p_cards"
    And the "Label" field should contain "Cards"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Service card" checkbox should be checked
