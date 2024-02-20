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
    Then I should see "TEST Page Publishing workflow" in the "Draft" row
    And I click "TEST Page Publishing workflow"
    And I select "needs_review" from "edit-new-state"
    And I press "Apply"

    Given I am an anonymous user
    When I visit "civictheme_page" "TEST Page Publishing workflow"
    Then the response status code should be 403

    Given I am logged in as a user with the "Content Approver" role
    When I visit "/admin/content/moderated"
    Then I should see "TEST Page Publishing workflow" in the "Needs Review" row
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

  @api
  Scenario: Verify the Publishing workflow for civictheme_alert
    Given I am logged in as a user with the "Content Author" role
    When I visit "node/add/civictheme_alert"
    And I fill in "Title" with "TEST Alert Publishing workflow"
    And I fill in "Message" with "TEST Message"
    And I select "error" from "edit-field-c-n-alert-type"
    And I select "draft" from "edit-moderation-state-0-state"
    And I press "Save"

    When I visit "/admin/content/moderated"
    Then I should see "TEST Alert Publishing workflow" in the "Draft" row
    Given I click "Edit" in the "TEST Alert Publishing workflow" row
    And I select "needs_review" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_alert" "TEST Alert Publishing workflow"
    Then the response status code should be 403

    Given I am logged in as a user with the "Content Author, Content Approver" role
    When I visit "/admin/content/moderated"
    Then I should see "TEST Alert Publishing workflow" in the "Needs Review" row
    Given I click "Edit" in the "TEST Alert Publishing workflow" row
    And I select "published" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_alert" "TEST Alert Publishing workflow"
    Then the response status code should be 200

    Given I am logged in as a user with the "Content Author, Content Approver" role
    When I edit "civictheme_alert" "TEST Alert Publishing workflow"
    And I select "archived" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_alert" "TEST Alert Publishing workflow"
    Then the response status code should be 403
  
  @api
  Scenario: Verify the Publishing workflow for civictheme_event
    Given I am logged in as a user with the "Content Author" role
    When I visit "node/add/civictheme_event"
    And I fill in "Title" with "TEST Event Publishing workflow"
    And I fill in "Body" with "TEST Body"
    And I fill in "edit-field-c-n-location-0-subform-field-c-p-address-0-value" with "Address 1"
    And I fill in "edit-field-c-n-location-0-subform-field-c-p-embed-url-0-uri" with "https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed"
    And I select "draft" from "edit-moderation-state-0-state"
    And I press "Save"

    When I visit "/admin/content/moderated"
    Then I should see "TEST Event Publishing workflow" in the "Draft" row
    Given I click "Edit" in the "TEST Event Publishing workflow" row
    And I select "needs_review" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_event" "TEST Event Publishing workflow"
    Then the response status code should be 403

    Given I am logged in as a user with the "Content Author, Content Approver" role
    When I visit "/admin/content/moderated"
    Then I should see "TEST Event Publishing workflow" in the "Needs Review" row
    Given I click "Edit" in the "TEST Event Publishing workflow" row
    And I select "published" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_event" "TEST Event Publishing workflow"
    Then the response status code should be 200

    Given I am logged in as a user with the "Content Author, Content Approver" role
    When I edit "civictheme_event" "TEST Event Publishing workflow"
    And I select "archived" from "edit-moderation-state-0-state"
    And I press "Save"

    Given I am an anonymous user
    When I visit "civictheme_event" "TEST Event Publishing workflow"
    Then the response status code should be 403
