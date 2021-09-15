@paragraph @civic_card_container
Feature: Tests the Card container paragraph

  Ensure that Card container paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Card container" in the "civic_card_container" row

  @api
  Scenario: Card container paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields"
    And I should see the text "field_p_cards" in the "Cards" row
    And I should see the text "field_p_column_count" in the "Column count" row
    And I should see the text "field_p_fill_width" in the "Fill width" row
    And I should see the text "field_p_link" in the "Link" row
    And I should see the text "field_p_title" in the "Title" row

  @api
  Scenario: Card container paragraph field_p_cards fields settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields/paragraph.civic_card_container.field_p_cards"
    And the "Label" field should contain "Cards"
    And I should see the option "Default" selected in "Reference method" dropdown
    Then the "Include the selected below" checkbox should be checked
    And the "Card task" checkbox should be checked
    And the "Card container" checkbox should not be checked
