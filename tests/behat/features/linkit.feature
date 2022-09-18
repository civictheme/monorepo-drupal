@civictheme @civictheme_linkit
Feature: Check that content linkit feature works correctly

  Ensure that LinkIt in WYSIWYG has access to Content pages and Document Media.

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
  Scenario: Check if linkit can lookup for Page Content.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Page linkit render"
    When I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I press the "field_c_n_components_civictheme_content_add_more" button
    And I wait for AJAX to finish

    When I click on ".cke_button__drupallink" element
    And I wait 2 seconds
    And I fill in the following:
      | attributes[href] | TEST Page Linkit |
    Then I press the "up" key on ".form-item-attributes-href input"
    And I wait 2 seconds
    Then I click on ".linkit-result-line.ui-menu-item" element
    Then I click on ".ui-dialog-buttonpane .js-form-submit" element
    And I wait 2 seconds
    And I check the box "Published"
    And I press "Save"

    When I visit "civictheme_page" "TEST Page linkit render"
    And I should see an ".ct-basic-content a[title='TEST Page Linkit'].ct-link" element

  @api @javascript
  Scenario: Check if linkit can lookup for Event Content.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Event linkit render"
    When I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I press the "field_c_n_components_civictheme_content_add_more" button
    And I wait for AJAX to finish

    When I click on ".cke_button__drupallink" element
    And I wait 2 seconds
    And I fill in the following:
      | attributes[href] | TEST Event Linkit |
    Then I press the "up" key on ".form-item-attributes-href input"
    And I wait 2 seconds
    Then I click on ".linkit-result-line.ui-menu-item" element
    Then I click on ".ui-dialog-buttonpane .js-form-submit" element
    And I wait 2 seconds
    And I check the box "Published"
    And I press "Save"

    When I visit "civictheme_page" "TEST Event linkit render"
    And I should see an ".ct-basic-content a[title='TEST Event Linkit'].ct-link" element


  @api @javascript
  Scenario: Check if linkit can lookup for Document Media.
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    And I fill in "Title" with "TEST Document Media linkit render"
    When I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I press the "field_c_n_components_civictheme_content_add_more" button
    And I wait for AJAX to finish

    When I click on ".cke_button__drupallink" element
    And I wait 2 seconds
    And I fill in the following:
      | attributes[href] | TEST CivicTheme PDF |
    Then I press the "up" key on ".form-item-attributes-href input"
    And I wait 2 seconds
    Then I click on ".linkit-result-line.ui-menu-item" element
    Then I click on ".ui-dialog-buttonpane .js-form-submit" element
    And I wait 2 seconds
    And I check the box "Published"
    And I press "Save"

    When I visit "civictheme_page" "TEST Document Media linkit render"
    And I should see an ".ct-basic-content a[title='TEST CivicTheme PDF'].ct-link" element
