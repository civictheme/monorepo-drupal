@civictheme @styleguide @field
Feature: Field, Textarea

  @api
  Scenario: Textarea
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200

    # Validate: Textarea, Title visible, no default value, no description, no error, so rows
    And I should see an ".ct-field.js-form-item-test-textarea-1" element
    And I should see an ".ct-field.js-form-item-test-textarea-1 label[for='edit-test-textarea-1']" element
    And I should not see a ".ct-field.js-form-item-test-textarea-1 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textarea-1 textarea" element
    And I should see an ".ct-field.js-form-item-test-textarea-1 textarea.ct-field__control.ct-textarea" element
    And I should see a ".ct-field.js-form-item-test-textarea-1 textarea[id='edit-test-textarea-1']" element
    And I should not see a ".ct-field.js-form-item-test-textarea-1 textarea[rows]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-1 textarea[required]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-1 textarea[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-1 .ct-field-message" element

    # Validate: Textarea, Title visible, default value, no description, no error, required, with attributes
    And I should see an ".ct-field.js-form-item-test-textarea-2" element
    And I should see a ".ct-field.js-form-item-test-textarea-2[data-wrapper-test='test-wrapper-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-textarea-2.test-wrapper-class" element
    And I should see an ".ct-field.js-form-item-test-textarea-2 label" element
    And I should see an ".ct-field.js-form-item-test-textarea-2 label[for='edit-test-textarea-2']" element
    And I should not see a ".ct-field.js-form-item-test-textarea-2 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textarea-2 textarea" element
    And I should see an ".ct-field.js-form-item-test-textarea-2 textarea.ct-field__control.ct-textarea" element
    And I should see a ".ct-field.js-form-item-test-textarea-2 textarea[id='edit-test-textarea-2']" element
    And the ".ct-field.js-form-item-test-textarea-2 textarea" element should contain "Default value"
    And I should see a ".ct-field.js-form-item-test-textarea-2 textarea[required]" element
    And I should see a ".ct-field.js-form-item-test-textarea-2 textarea[rows='10']" element
    And I should see a ".ct-field.js-form-item-test-textarea-2 textarea[data-test='test-attribute-value']" element
    And I should not see a ".ct-field.js-form-item-test-textarea-2 textarea[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-2 textarea[error]" element
    And I should see a ".ct-field.js-form-item-test-textarea-2 textarea[required]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-2 textarea[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-2 .ct-field-message" element

    # Validate: Textarea, Title visually hidden, default value, description, no error
    And I should see an ".ct-field.js-form-item-test-textarea-3" element
    And I should see an ".ct-field.js-form-item-test-textarea-3 label" element
    And I should see an ".ct-field.js-form-item-test-textarea-3 label[for='edit-test-textarea-3']" element
    And I should see an ".ct-field.js-form-item-test-textarea-3 label.ct-visually-hidden" element
    And I should see a ".ct-field.js-form-item-test-textarea-3 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textarea-3 textarea" element
    And I should see an ".ct-field.js-form-item-test-textarea-3 textarea.ct-field__control.ct-textarea" element
    And the ".ct-field.js-form-item-test-textarea-3 textarea" element should contain "Textarea, Title visually hidden, default value, description, no error"
    And I should not see a ".ct-field.js-form-item-test-textarea-3 textarea[required]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-3 textarea[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-3 textarea[required]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-3 textarea[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-3 .ct-field-message" element

    # Validate: Textarea, Title hidden, no default value, placeholder, description, required, error
    And I should see an ".ct-field.js-form-item-test-textarea-4" element
    And I should not see an ".ct-field.js-form-item-test-textarea-4 label" element
    And I should see a ".ct-field.js-form-item-test-textarea-4 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textarea-4 textarea" element
    And I should see an ".ct-field.js-form-item-test-textarea-4 textarea.ct-field__control.ct-textarea" element
    And I should see a ".ct-field.js-form-item-test-textarea-4 textarea[required]" element
    And I should see a ".ct-field.js-form-item-test-textarea-4 textarea[placeholder='Placeholder. Textarea, Title hidden, default value, placeholder, description, required, error']" element
    And I should see a ".ct-field.js-form-item-test-textarea-4 textarea[required]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-4 textarea[disabled]" element
    And I should see a ".ct-field.js-form-item-test-textarea-4 .ct-field-message" element

    # Validate: Textarea, Title visible, default value, no description, disabled
    And I should see an ".ct-field.js-form-item-test-textarea-5" element
    And I should see an ".ct-field.js-form-item-test-textarea-5 label" element
    And I should not see a ".ct-field.js-form-item-test-textarea-5 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-textarea-5 textarea" element
    And I should see an ".ct-field.js-form-item-test-textarea-5 textarea.ct-field__control.ct-textarea" element
    And I should not see a ".ct-field.js-form-item-test-textarea-5 textarea[required]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-5 textarea[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-5 textarea[required]" element
    And I should see a ".ct-field.js-form-item-test-textarea-5 textarea[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-textarea-5 .ct-field-message" element
