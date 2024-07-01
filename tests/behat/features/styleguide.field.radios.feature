@civictheme @styleguide @field
Feature: Field, Radios

  @api
  Scenario: Radios
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200

    # Validate: Radios, Title visible, no default value, no description, no error
    And I should see an ".ct-field.js-form-item-test-radios-1" element
    And I should see an ".ct-field.js-form-item-test-radios-1 label" element
    And I should see an ".ct-field.js-form-item-test-radios-1 label:contains('Radios, Title visible, no default value, no description, no error')" element
    And I should not see a ".ct-field.js-form-item-test-radios-1 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-radios-1 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-radios-1 input[type='radio']" element
    And I should see a ".ct-field.js-form-item-test-radios-1 input[id='edit-test-radios-1-option-1']" element
    And I should see a ".ct-field.js-form-item-test-radios-1 input[id='edit-test-radios-1-option-2']" element
    And I should see a ".ct-field.js-form-item-test-radios-1 input[id='edit-test-radios-1-option-3']" element
    And I should see a ".ct-field.js-form-item-test-radios-1 input[id='edit-test-radios-1-option-4']" element
    And I should see a ".ct-field.js-form-item-test-radios-1 input[id='edit-test-radios-1-option-5']" element
    And I should not see a ".ct-field.js-form-item-test-radios-1 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-radios-1 input[disabled]" element

    # Validate: Radios, Title visible, default value, no description, no error, required, with attributes
    And I should see an ".ct-field.js-form-item-test-radios-2" element
    And I should see a ".ct-field.js-form-item-test-radios-2.test-wrapper-class" element
    And I should not see a ".ct-field.js-form-item-test-radios-2.test-class" element
    And I should see a ".ct-field.js-form-item-test-radios-2[data-wrapper-test='test-wrapper-attribute-value']" element
    And I should not see a ".ct-field.js-form-item-test-radios-2[data-test='test-attribute-value']" element
    And I should see an ".ct-field.js-form-item-test-radios-2 label" element
    And I should see an ".ct-field.js-form-item-test-radios-2 label:contains('Radios, Title visible, default value, no description, no error, required, with attributes')" element
    And I should not see a ".ct-field.js-form-item-test-radios-2 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-radios-2 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[type='radio']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-1']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-1'][checked]" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-1'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-1'].test-class" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-2']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-2'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-2'].test-class" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-3']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-3'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-3'].test-class" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-4']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-4'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-4'].test-class" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-5']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-5'][data-test='test-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[id='edit-test-radios-2-option-5'].test-class" element
    And I should see a ".ct-field.js-form-item-test-radios-2 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-radios-2 input[disabled]" element

    # Validate: Radios, Title visually hidden, default value, description, no error
    And I should see an ".ct-field.js-form-item-test-radios-3" element
    And I should see an ".ct-field.js-form-item-test-radios-3 label.ct-visually-hidden" element
    And I should see a ".ct-field.js-form-item-test-radios-3 label:contains('Radios, Title visually hidden, default value, description, no error')" element
    And I should see a ".ct-field.js-form-item-test-radios-3 .ct-field-description:contains('This is a description')" element
    And I should see a ".ct-field.js-form-item-test-radios-3 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-radios-3 input[type='radio']" element
    And I should see a ".ct-field.js-form-item-test-radios-3 input[id='edit-test-radios-3-option-1']" element
    And I should see a ".ct-field.js-form-item-test-radios-3 input[id='edit-test-radios-3-option-2'][checked]" element
    And I should see a ".ct-field.js-form-item-test-radios-3 input[id='edit-test-radios-3-option-3']" element
    And I should see a ".ct-field.js-form-item-test-radios-3 input[id='edit-test-radios-3-option-4']" element
    And I should see a ".ct-field.js-form-item-test-radios-3 input[id='edit-test-radios-3-option-5']" element
    And I should not see a ".ct-field.js-form-item-test-radios-3 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-radios-3 input[disabled]" element

    # Validate: Radios, Title hidden, no default value, description, required, error
    And I should see an ".ct-field.js-form-item-test-radios-4" element
    And I should not see a ".ct-field.js-form-item-test-radios-4 label.ct__title" element
    And I should see a ".ct-field.js-form-item-test-radios-4 .ct-field-description:contains('This is a description')" element
    And I should see a ".ct-field.js-form-item-test-radios-4 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[type='radio']" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[id='edit-test-radios-4-option-1']" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[id='edit-test-radios-4-option-2']" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[id='edit-test-radios-4-option-3']" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[id='edit-test-radios-4-option-4']" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[id='edit-test-radios-4-option-5']" element
    And I should see a ".ct-field.js-form-item-test-radios-4 input[required]" element
    And I should not see a ".ct-field.js-form-item-test-radios-4 input[disabled]" element
    And I should see a ".ct-field.js-form-item-test-radios-4 .ct-field-message:contains('This is an error message')" element

    # Validate: Radios, Title visible, default value, no description, disabled
    And I should see an ".ct-field.js-form-item-test-radios-5" element
    And I should see an ".ct-field.js-form-item-test-radios-5 label" element
    And I should see an ".ct-field.js-form-item-test-radios-5 label:contains('Radios, Title visible, default value, no description, disabled')" element
    And I should not see a ".ct-field.js-form-item-test-radios-5 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-radios-5 ul.ct-item-list" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[type='radio']" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[id='edit-test-radios-5-option-1'][checked]" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[id='edit-test-radios-5-option-2']" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[id='edit-test-radios-5-option-3']" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[id='edit-test-radios-5-option-4']" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[id='edit-test-radios-5-option-5']" element
    And I should not see a ".ct-field.js-form-item-test-radios-5 input[required]" element
    And I should see a ".ct-field.js-form-item-test-radios-5 input[disabled]" element
