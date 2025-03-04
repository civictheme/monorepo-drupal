@p0 @civictheme @civictheme_linkit
Feature: Linkit works correctly on pages

  Background:
    Given managed file:
      | filename     | uri                                   | path         |
      | test_pdf.pdf | public://civictheme_test/test_pdf.pdf | test_pdf.pdf |

    And "civictheme_document" media:
      | name                | field_c_m_document |
      | TEST CivicTheme PDF | test_pdf.pdf       |

    And "civictheme_page" content:
      | title            | status |
      | TEST Page Linkit | 1      |

    And "civictheme_event" content:
      | title             | status |
      | TEST Event Linkit | 1      |

  @api @javascript
  Scenario: Check if Linkit can lookup for Page Content.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Page linkit render"
    When I press the "field_c_n_components_civictheme_content_add_more" button
    And I wait for AJAX to finish

    And I scroll to an element with id "edit-field-c-n-show-toc-value"
    When I click on "[data-cke-tooltip-text^=Link]" element
    And I wait 2 seconds
    And I fill in the following:
      | Link URL | TEST Page Linkit |
    Then I press the "down" key on ".ck-link-form .ck-input"
    And I wait 2 seconds
    And save screenshot
    Then I click on ".linkit-result-line.ui-menu-item" element
    Then I click on ".ck-button-save" element
    And I wait 2 seconds
    And I select "Published" from "edit-moderation-state-0-state"
    And I press "Save"

    When I visit "civictheme_page" "TEST Page linkit render"
    And I should see an ".ct-basic-content a[title='TEST Page Linkit'].ct-content-link" element

  @api @javascript
  Scenario: Check if Linkit can lookup for Event Content.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Event linkit render"
    When I press the "field_c_n_components_civictheme_content_add_more" button
    And I wait for AJAX to finish

    And I scroll to an element with id "edit-field-c-n-show-toc-value"
    When I click on "[data-cke-tooltip-text^=Link]" element
    And I wait 2 seconds
    And I fill in the following:
      | Link URL | TEST Event Linkit |
    Then I press the "down" key on ".ck-link-form .ck-input"
    And I wait 2 seconds
    And save screenshot
    Then I click on ".linkit-result-line.ui-menu-item" element
    Then I click on ".ck-button-save" element
    And I wait 2 seconds
    And I select "Published" from "edit-moderation-state-0-state"
    And I press "Save"

    When I visit "civictheme_page" "TEST Event linkit render"
    And I should see an ".ct-basic-content a[title='TEST Event Linkit'].ct-content-link" element

  @api @javascript
  Scenario: Check if Linkit can lookup for Document Media.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Document Media linkit render"
    When I press the "field_c_n_components_civictheme_content_add_more" button
    And I wait for AJAX to finish

    And I scroll to an element with id "edit-field-c-n-show-toc-value"
    When I click on "[data-cke-tooltip-text^=Link]" element
    And I wait 2 seconds
    And I fill in the following:
      | Link URL | TEST CivicTheme PDF |
    Then I press the "down" key on ".ck-link-form .ck-input"
    And I wait 2 seconds
    And save screenshot
    Then I click on ".linkit-result-line.ui-menu-item" element
    Then I click on ".ck-button-save" element
    And I wait 2 seconds
    And I select "Published" from "edit-moderation-state-0-state"
    And I press "Save"

    When I visit "civictheme_page" "TEST Document Media linkit render"
    And I should see an ".ct-basic-content a[title='TEST CivicTheme PDF'].ct-content-link" element
