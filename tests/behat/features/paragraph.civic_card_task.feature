@paragraph @civic_card_task
Feature: Tests the Card task paragraph

  Ensure that Card task paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Card task" in the "civic_card_task" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_task/fields"
    And I should see the text "field_p_icon" in the "Icon" row
    And I should see the text "field_p_link" in the "Link" row
    And I should see the text "field_p_summary" in the "Summary" row
    And I should see the text "field_p_title" in the "Title" row
