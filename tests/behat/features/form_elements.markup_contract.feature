@p1 @civictheme @form_contract
Feature: Form element markup contract

  Baseline assertions for form element markup: attributes, classes, label
  association and fieldset structure. These capture behaviour that is correct
  today and must keep passing unchanged through the form rendering refactor.

  @api
  Scenario: Text-like webform elements associate labels with controls
    Given I go to "form/civictheme-test-webform-fields"
    Then the field with id "edit-text-field" should have an associated label displayed before it
    And the field with id "edit-email" should have an associated label displayed before it
    And the field with id "edit-telephone" should have an associated label displayed before it
    And the field with id "edit-number" should have an associated label displayed before it
    And the field with id "edit-url" should have an associated label displayed before it
    And the field with id "edit-textarea" should have an associated label displayed before it
    And the field with id "edit-color" should have an associated label displayed before it
    And the field with id "edit-date" should have an associated label displayed before it
    And the field with id "edit-select" should have an associated label displayed before it
    And the field with id "edit-entity-autocomplete" should have an associated label displayed before it
    And the field with id "edit-file-upload" should have an associated label displayed before it
    And the element "input#edit-text-field" should have a "name" attribute equal to "text_field"
    And the element "input#edit-text-field" should have a "data-drupal-selector" attribute equal to "edit-text-field"
    And I should see the "input#edit-text-field" element with a "class" attribute containing "ct-textfield"
    And I should see the "textarea#edit-textarea" element with a "class" attribute containing "ct-textarea"

  @api
  Scenario: Option groups render fieldsets with legends and item labels
    Given I go to "form/civictheme-test-webform-fields"
    Then the element "#edit-checkboxes--wrapper fieldset" should contain a legend as its first child
    And the element "#edit-radios--wrapper fieldset" should contain a legend as its first child
    And the field with id "edit-checkboxes-all" should have an associated label displayed after it
    And I should see the "#edit-checkboxes input[type=checkbox]" element with a "class" attribute containing "ct-checkbox"
    And I should see the "#edit-radios input[type=radio]" element with a "class" attribute containing "ct-radio"

  @api
  Scenario: Select elements render their options with values
    Given I go to "form/civictheme-test-webform-fields"
    Then the element "select#edit-select" should have a "name" attribute equal to "select"
    And the element "select#edit-select option[value=Afghanistan]" should have a "value" attribute equal to "Afghanistan"

  @api
  Scenario: Display-only webform markup elements render as basic content, not fields
    Given I go to "form/civictheme-test-webform-fields"
    Then I should see the "#edit-advanced-html-text" element with a "class" attribute containing "ct-basic-content"
    And I should see the "#edit-advanced-html-text" element with a "class" attribute containing "js-form-item"
    And I should not see a "#edit-advanced-html-text.ct-field" element
    And I should not see a "#edit-advanced-html-text .container" element
    And I should not see a "#edit-markup-container .container" element

  @api
  Scenario: Required enquiry fields carry the required attribute and marker
    Given I go to "enquiry"
    Then the element "input#edit-first-name" should have a "required" attribute
    And the element "input#edit-email" should have a "required" attribute
    And the element "textarea#edit-enquiry" should have a "required" attribute
    And I should see "(required)" in the "label[for=edit-first-name]" element
    And I should see the "[data-drupal-selector=edit-actions-submit]" element with a "class" attribute containing "ct-button"
    And the element "[data-drupal-selector=edit-actions-submit]" should have a "value" attribute equal to "Send"

  @api
  Scenario: Submitting the enquiry form empty renders field-level error messages
    Given I go to "enquiry"
    When I press "Send"
    Then I should see "Your first name is required."
    And the element "input#edit-first-name" should have an "aria-invalid" attribute equal to "true"
    And I should see the "#edit-first-name--error-message" element with a "class" attribute containing "ct-field-message"
    And I should see the "input#edit-first-name" element with a "class" attribute containing "ct-textfield--is-invalid"
