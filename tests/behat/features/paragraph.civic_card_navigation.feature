@paragraph @civic_card_navigation
Feature: Tests the Card container paragraph

  Ensure that Card container paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Navigation card" in the "civic_card_navigation" row

  @api
  Scenario: Card container paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_navigation/fields"
    And I should see the text "field_p_theme" in the "Theme" row
    And I should see the text "field_p_title" in the "Title" row
    And I should see the text "field_p_image" in the "Image" row
    And I should see the text "field_p_link" in the "Link" row
    And I should see the text "field_p_summary" in the "Summary" row

  @api
  Scenario: Card container paragraph field_p_cards fields settings.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_container/fields/paragraph.civic_card_container.field_p_cards"
    And the "Label" field should contain "Cards"
    Then the option "Default" from select "Reference method" is selected
    Then the "Include the selected below" checkbox should be checked
    And the "Navigation card" checkbox should be checked
