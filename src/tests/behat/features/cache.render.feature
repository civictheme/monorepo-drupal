@p1 @civictheme @cache
Feature: Validate page titles are not cached as Access Denied

  Ensure that Page titles can be viewed correctly.

  @api
  Scenario: CivicTheme page can be viewed without cached page titles
    Given I am logged in as a user with the "Content Author" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "[TEST] Page Title Test"
    And I select "draft" from "edit-moderation-state-0-state"
    And I press "Save"
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Page Title Test"
    And I should see the text "Access Denied"
    Given I am logged in as a user with the "Content Approver" role
    When I visit "/admin/content/moderated"
    Then I should see "[TEST] Page Title Test" in the "Draft" row
    And I click "[TEST] Page Title Test"
    And I select "published" from "edit-new-state"
    And I press "Apply"
    Then I save screenshot
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] Page Title Test"
    And I should see the text "[TEST] Page Title Test"
    And I should not see the text "Access Denied"
