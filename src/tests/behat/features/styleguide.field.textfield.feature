@civictheme @styleguide @field
Feature: Field, Textfield

  @api
  Scenario: Textfield
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200

    # Validate: Textfield, Title visible, no default value, no description, no error, so size
    And I should see an ".ct-field.js-form-item-test-textfield-1" element
    And I should see an ".ct-field.js-form-item-test-textfield-1 label[for='edit-test-textfield-1']" element
    And I should not see a ".ct-field.js-form-item-test-textfield-1 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textfield-1 input" element
    And I should see an ".ct-field.js-form-item-test-textfield-1 input.ct-field__control.ct-textfield" element
    And I should see a ".ct-field.js-form-item-test-textfield-1 input[id='edit-test-textfield-1']" element
    And I should see a ".ct-field.js-form-item-test-textfield-1 input[maxlength='128']" element
    And I should not see a ".ct-field.js-form-item-test-textfield-1 input[value='']" element
    And I should not see a ".ct-field.js-form-item-test-textfield-1 input[size]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-1 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-1 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-1 .ct-field-message" element

    # Validate: Textfield, Title visible, default value, no description, no error, required, with attributes
    And I should see an ".ct-field.js-form-item-test-textfield-2" element
    And I should see a ".ct-field.js-form-item-test-textfield-2[data-wrapper-test='test-wrapper-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-textfield-2.test-wrapper-class" element
    And I should see an ".ct-field.js-form-item-test-textfield-2 label" element
    And I should see an ".ct-field.js-form-item-test-textfield-2 label[for='edit-test-textfield-2']" element
    And I should not see a ".ct-field.js-form-item-test-textfield-2 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input" element
    And I should see an ".ct-field.js-form-item-test-textfield-2 input.ct-field__control.ct-textfield" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[id='edit-test-textfield-2']" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[value='Default value']" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[required]" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[size='10']" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[maxlength='10']" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[data-test='test-attribute-value']" element
    And I should not see a ".ct-field.js-form-item-test-textfield-2 input[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-2 input[error]" element
    And I should see a ".ct-field.js-form-item-test-textfield-2 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-2 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-2 .ct-field-message" element

    # Validate: Textfield, Title visually hidden, default value, description, no error
    And I should see an ".ct-field.js-form-item-test-textfield-3" element
    And I should see an ".ct-field.js-form-item-test-textfield-3 label" element
    And I should see an ".ct-field.js-form-item-test-textfield-3 label[for='edit-test-textfield-3']" element
    And I should see an ".ct-field.js-form-item-test-textfield-3 label.ct-visually-hidden" element
    And I should see a ".ct-field.js-form-item-test-textfield-3 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textfield-3 input" element
    And I should see an ".ct-field.js-form-item-test-textfield-3 input.ct-field__control.ct-textfield" element
    And I should see a ".ct-field.js-form-item-test-textfield-3 input[value='Textfield, Title visually hidden, default value, description, no error']" element
    And I should not see a ".ct-field.js-form-item-test-textfield-3 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-3 input[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-3 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-3 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-3 .ct-field-message" element

    # Validate: Textfield, Title hidden, no default value, placeholder, description, required, error
    And I should see an ".ct-field.js-form-item-test-textfield-4" element
    And I should not see an ".ct-field.js-form-item-test-textfield-4 label" element
    And I should see a ".ct-field.js-form-item-test-textfield-4 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textfield-4 input" element
    And I should see an ".ct-field.js-form-item-test-textfield-4 input.ct-field__control.ct-textfield" element
    And I should see a ".ct-field.js-form-item-test-textfield-4 input[required]" element
    And I should see a ".ct-field.js-form-item-test-textfield-4 input[placeholder='Placeholder. Textfield, Title hidden, default value, placeholder, description, required, error']" element
    And I should see a ".ct-field.js-form-item-test-textfield-4 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-4 input[disabled]" element
    And I should see a ".ct-field.js-form-item-test-textfield-4 .ct-field-message" element

    # Validate: Textfield, Title visible, default value, no description, disabled
    And I should see an ".ct-field.js-form-item-test-textfield-5" element
    And I should see an ".ct-field.js-form-item-test-textfield-5 label" element
    And I should not see a ".ct-field.js-form-item-test-textfield-5 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textfield-5 input" element
    And I should see an ".ct-field.js-form-item-test-textfield-5 input.ct-field__control.ct-textfield" element
    And I should not see a ".ct-field.js-form-item-test-textfield-5 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-5 input[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-5 input[required]" element
    And I should see a ".ct-field.js-form-item-test-textfield-5 input[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textfield-5 .ct-field-message" element
