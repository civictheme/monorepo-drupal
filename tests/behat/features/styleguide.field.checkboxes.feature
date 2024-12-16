@civictheme @styleguide @field
Feature: Field, Checkboxes

  @api
  Scenario: Checkboxes
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200

    # Validate: Checkboxes, Title visible, no default value, no description, no error
    And I should see an ".ct-field.js-form-item-test-checkboxes-1" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-1 label" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-1 label:contains('Checkboxes, Title visible, no default value, no description, no error')" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-1 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 input[id='edit-test-checkboxes-1-option-1']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 input[id='edit-test-checkboxes-1-option-2']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 input[id='edit-test-checkboxes-1-option-3']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 input[id='edit-test-checkboxes-1-option-4']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-1 input[id='edit-test-checkboxes-1-option-5']" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-1.ct-field--required input" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-1 input[disabled]" element

    # Validate: Checkboxes, Title visible, default value, no description, no error, required, with attributes
    And I should see an ".ct-field.js-form-item-test-checkboxes-2" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2.test-wrapper-class" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-2.test-class" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2[data-wrapper-test='test-wrapper-attribute-value']" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-2[data-test='test-attribute-value']" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-2 label" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-2 label:contains('Checkboxes, Title visible, default value, no description, no error, required, with attributes')" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-2 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-1']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-1'][checked]" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-1'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-1'].test-class" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-2']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-2'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-2'].test-class" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-3']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-3'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-3'].test-class" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-4']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-4'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-4'].test-class" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-5']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-5'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2 input[id='edit-test-checkboxes-2-option-5'].test-class" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-2.ct-field--required input" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-2.ct-field--disabled input[disabled]" element

    # Validate: Checkboxes, Title visually hidden, default value, description, no error
    And I should see an ".ct-field.js-form-item-test-checkboxes-3" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-3 label.ct-visually-hidden" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 label:contains('Checkboxes, Title visually hidden, default value, description, no error')" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 .ct-field-description:contains('This is a description')" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 input[id='edit-test-checkboxes-3-option-1']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 input[id='edit-test-checkboxes-3-option-2'][checked]" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 input[id='edit-test-checkboxes-3-option-3']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 input[id='edit-test-checkboxes-3-option-4']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-3 input[id='edit-test-checkboxes-3-option-5']" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-3.ct-field--required input" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-3.ct-field--disabled input[disabled]" element

    # Validate: Checkboxes, Title hidden, no default value, description, required, error
    And I should see an ".ct-field.js-form-item-test-checkboxes-4" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-4 label.ct__title" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 .ct-field-description:contains('This is a description')" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 input[id='edit-test-checkboxes-4-option-1']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 input[id='edit-test-checkboxes-4-option-2']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 input[id='edit-test-checkboxes-4-option-3']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 input[id='edit-test-checkboxes-4-option-4']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 input[id='edit-test-checkboxes-4-option-5']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4.ct-field--required input" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-4.ct-field--disabled input[disabled]" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-4 .ct-field-message:contains('This is an error message')" element

    # Validate: Checkboxes, Title visible, default value, no description, disabled
    And I should see an ".ct-field.js-form-item-test-checkboxes-5" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-5 label" element
    And I should see an ".ct-field.js-form-item-test-checkboxes-5 label:contains('Checkboxes, Title visible, default value, no description, disabled')" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-5 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 input[type='checkbox']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 input[id='edit-test-checkboxes-5-option-1'][checked]" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 input[id='edit-test-checkboxes-5-option-2']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 input[id='edit-test-checkboxes-5-option-3']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 input[id='edit-test-checkboxes-5-option-4']" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5 input[id='edit-test-checkboxes-5-option-5']" element
    And I should not see a ".ct-field.js-form-item-test-checkboxes-5.ct-field--required input" element
    And I should see a ".ct-field.js-form-item-test-checkboxes-5.ct-field--disabled input[disabled]" element
