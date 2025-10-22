@civictheme @styleguide @field
Feature: Field, Checkbox

  @api
  Scenario: Checkbox
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200

    # Validate: Checkbox, Title visible, no default value, no description, no error
    And I should see an ".ct-field.js-form-item-test-checkbox-1" element
    And I should see an ".ct-field.js-form-item-test-checkbox-1 label[for='edit-test-checkbox-1']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-1 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkbox-1 input[type='checkbox']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-1 input[type='checkbox'][checked]" element
    And I should see a ".ct-field.js-form-item-test-checkbox-1 input.ct-field__control.ct-checkbox.form-checkbox" element
    And I should see a ".ct-field.js-form-item-test-checkbox-1 input[id='edit-test-checkbox-1']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-1 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-1 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-1 .ct-field-message" element

    # Validate: Checkbox, Title visible, default value, no description, no error, required, with attributes
    And I should see an ".ct-field.js-form-item-test-checkbox-2" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2[data-wrapper-test='test-wrapper-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2.test-wrapper-class" element
    And I should see an ".ct-field.js-form-item-test-checkbox-2 label[for='edit-test-checkbox-2']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-2 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2 input[type='checkbox'][checked]" element
    And I should see an ".ct-field.js-form-item-test-checkbox-2 input.ct-field__control.ct-checkbox.test-class.form-checkbox.required" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2 input[id='edit-test-checkbox-2']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2 input[required]" element
    And I should see a ".ct-field.js-form-item-test-checkbox-2 input[data-test='test-attribute-value']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-2 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-2 .ct-field-message" element

    # Validate: Checkbox, Title visually hidden, default value, description, no error
    And I should see an ".ct-field.js-form-item-test-checkbox-3" element
    And I should see an ".ct-field.js-form-item-test-checkbox-3 label[for='edit-test-checkbox-3']" element
    And I should see an ".ct-field.js-form-item-test-checkbox-3 legend.ct-visually-hidden" element
    And I should see a ".ct-field.js-form-item-test-checkbox-3 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkbox-3 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-3 input[type='checkbox'][checked]" element
    And I should see an ".ct-field.js-form-item-test-checkbox-3 input.ct-field__control.ct-checkbox.form-checkbox" element
    And I should see a ".ct-field.js-form-item-test-checkbox-3 input[id='edit-test-checkbox-3']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-3 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-3 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-3 .ct-field-message" element

    # Validate: Checkbox, Title hidden, no default value, description, required, error
    And I should see an ".ct-field.js-form-item-test-checkbox-4" element
    And I should not see an ".ct-field.js-form-item-test-checkbox-4 label.ct-field__title" element
    And I should see an ".ct-field.js-form-item-test-checkbox-4 label[for='edit-test-checkbox-4']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-4 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkbox-4 input[type='checkbox']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-4 input[type='checkbox'][checked]" element
    And I should see an ".ct-field.js-form-item-test-checkbox-4 input.ct-field__control.ct-checkbox.ct-checkbox--is-invalid.form-checkbox.required" element
    And I should see a ".ct-field.js-form-item-test-checkbox-4 input[id='edit-test-checkbox-4']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-4 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-4 input[disabled]" element
    And I should see a ".ct-field.js-form-item-test-checkbox-4 .ct-field-message" element

    # Validate: Checkbox, Title visible, default value, no description, disabled
    And I should see an ".ct-field.js-form-item-test-checkbox-5" element
    And I should see an ".ct-field.js-form-item-test-checkbox-5 legend.ct-field__title" element
    And I should not see an ".ct-field.js-form-item-test-checkbox-5 label.ct-field__title[for='edit-test-checkbox-5']" element
    And I should see an ".ct-field.js-form-item-test-checkbox-5 label[for='edit-test-checkbox-5']" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-5 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkbox-5 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-5 input[type='checkbox'][checked]" element
    And I should see an ".ct-field.js-form-item-test-checkbox-5 input.ct-field__control.ct-checkbox.form-checkbox" element
    And I should see a ".ct-field.js-form-item-test-checkbox-5 input[id='edit-test-checkbox-5']" element
    And I should see a ".ct-field.js-form-item-test-checkbox-5 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-5 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-checkbox-5 .ct-field-message" element
