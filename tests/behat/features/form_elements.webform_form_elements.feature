@p1 @civictheme @civictheme_form_updates @civictheme_webform
Feature: Test a sample of the webform elements in the webform

  @api
  Scenario: Fields appear as expected
    Given I am an anonymous user
    When I visit "form/civictheme-test-webform-fields"

    Then I should see "Civictheme Test Webform - Fields" in the ".ct-banner__title" element
