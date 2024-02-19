@p1 @civictheme @publishing_workflow
Feature: Publishing workflow

  @api
  Scenario: Verify the Publishing workflow for civictheme_page
    Given I am logged in as a user with the "Content Author" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Page Publishing workflow"
    And I select "draft" from "edit-moderation-state-0-state"
    And I press "Save"

    When I visit "/admin/content/moderated"
    Then I should see the text "TEST Page Publishing workflow" in the same row as "Draft"
    And I click "TEST Page Publishing workflow"
    And I select "needs_review" from "edit-new-state"
    And I press "Apply"

    Given I am an anonymous user
    When I visit "civictheme_page" "TEST Page Publishing workflow"
    Then the response status code should be 403

    Given I am logged in as a user with the "Content Approver" role
    When I visit "/admin/content/moderated"
    Then I should see the text "TEST Page Publishing workflow" in the same row as "Needs Review"
    And I click "TEST Page Publishing workflow"
    And I select "published" from "edit-new-state"
    And I press "Apply"

    Given I am an anonymous user
    When I visit "civictheme_page" "TEST Page Publishing workflow"
    Then the response status code should be 200

    Given I am logged in as a user with the "Content Author, Content Approver" role
    When I edit "civictheme_page" "TEST Page Publishing workflow"
    And I select "archived" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_page" "TEST Page Publishing workflow"
    Then the response status code should be 403
