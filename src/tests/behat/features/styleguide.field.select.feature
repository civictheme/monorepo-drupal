@civictheme @styleguide @field
Feature: Field, Select

  @api
  Scenario: Select
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/appearance/styleguide"
    Then the response status code should be 200

    # Validate: Select, Title visible, no default value, no description, no error, no size
    And I should see an ".ct-field.js-form-item-test-select-1" element
    And I should see an ".ct-field.js-form-item-test-select-1 label[for='edit-test-select-1']" element
    And I should not see a ".ct-field.js-form-item-test-select-1 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-select-1 select" element
    And I should see a ".ct-field.js-form-item-test-select-1 select.ct-field__control.ct-select" element
    And I should see a ".ct-field.js-form-item-test-select-1 select[id='edit-test-select-1']" element
    And I should not see a ".ct-field.js-form-item-test-select-1 select[size]" element
    And I should not see a ".ct-field.js-form-item-test-select-1 select[required]" element
    And I should not see a ".ct-field.js-form-item-test-select-1 select[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-select-1 .ct-field-message" element
    And I should see a ".ct-field.js-form-item-test-select-1 select option[value='']" element
    And I should see a ".ct-field.js-form-item-test-select-1 select option[value='option_1']" element
    And I should see a ".ct-field.js-form-item-test-select-1 select option[value='option_2']" element

    # Validate: Select, Title visible, default value, no description, no error, required, with attributes, multiselect
    And I should see an ".ct-field.js-form-item-test-select-2" element
    And I should see a ".ct-field.js-form-item-test-select-2[data-wrapper-test='test-wrapper-attribute-value']" element
    And I should see a ".ct-field.js-form-item-test-select-2.test-wrapper-class" element
    And I should see an ".ct-field.js-form-item-test-select-2 label" element
    And I should see an ".ct-field.js-form-item-test-select-2 label[for='edit-test-select-2']" element
    And I should not see a ".ct-field.js-form-item-test-select-2 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-select-2 select" element
    And I should see an ".ct-field.js-form-item-test-select-2 select.ct-field__control.ct-select" element
    And I should see a ".ct-field.js-form-item-test-select-2 select[id='edit-test-select-2']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select[required]" element
    And I should see a ".ct-field.js-form-item-test-select-2 select[size='5']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select[data-test='test-attribute-value']" element
    And I should not see a ".ct-field.js-form-item-test-select-2 select[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-select-2 select[error]" element
    And I should not see a ".ct-field.js-form-item-test-select-2 select[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-select-2 .ct-field-message" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='option_1'][selected='selected']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='option_2']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='option_3']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='option_4']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='option_5']" element
    And I should see a ".ct-field.js-form-item-test-select-2 select option[value='option_6']" element

    # Validate: Select, Title visually hidden, default value, description before, no error
    And I should see an ".ct-field.js-form-item-test-select-3" element
    And I should see an ".ct-field.js-form-item-test-select-3 label" element
    And I should see an ".ct-field.js-form-item-test-select-3 label[for='edit-test-select-3']" element
    And I should see an ".ct-field.js-form-item-test-select-3 label.ct-visually-hidden" element
    And I should see a ".ct-field.js-form-item-test-select-3 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-select-3 select" element
    And I should see an ".ct-field.js-form-item-test-select-3 select.ct-field__control.ct-select" element
    And I should see a ".ct-field.js-form-item-test-select-3 select[id='edit-test-select-3']" element
    And I should not see a ".ct-field.js-form-item-test-select-3 select[required]" element
    And I should not see a ".ct-field.js-form-item-test-select-3 select[placeholder]" element
    And I should not see a ".ct-field.js-form-item-test-select-3 select[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-select-3 .ct-field-message" element
    And I should see a ".ct-field.js-form-item-test-select-3 select option[value='']" element
    And I should see a ".ct-field.js-form-item-test-select-3 select option[value='option_1']" element
    And I should see a ".ct-field.js-form-item-test-select-3 select option[value='option_2'][selected='selected']" element

    # Validate: Select, Title hidden, no default value, description after, required, error
    And I should see an ".ct-field.js-form-item-test-select-4" element
    And I should not see an ".ct-field.js-form-item-test-select-4 label" element
    And I should see a ".ct-field.js-form-item-test-select-4 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-select-4 select" element
    And I should see an ".ct-field.js-form-item-test-select-4 select.ct-field__control.ct-select" element
    And I should see a ".ct-field.js-form-item-test-select-4 select[required]" element
    And I should not see a ".ct-field.js-form-item-test-select-4 select[disabled]" element
    And I should see a ".ct-field.js-form-item-test-select-4 .ct-field-message" element
    And I should see a ".ct-field.js-form-item-test-select-4 select option[value=''][selected='selected']" element
    And I should see a ".ct-field.js-form-item-test-select-4 select option[value='option_1']" element
    And I should see a ".ct-field.js-form-item-test-select-4 select option[value='option_2']" element

    # Validate: Select, Title visible, default value, no description, disabled
    And I should see an ".ct-field.js-form-item-test-select-5" element
    And I should see an ".ct-field.js-form-item-test-select-5 label" element
    And I should not see a ".ct-field.js-form-item-test-select-5 .ct-field-description" element
    And I should see a ".ct-field.js-form-item-test-select-5 select" element
    And I should see an ".ct-field.js-form-item-test-select-5 select.ct-field__control.ct-select" element
    And I should not see a ".ct-field.js-form-item-test-select-5 select[required]" element
    And I should not see a ".ct-field.js-form-item-test-select-5 select[placeholder]" element
    And I should see a ".ct-field.js-form-item-test-select-5 select[disabled]" element
    And I should not see a ".ct-field.js-form-item-test-select-5 .ct-field-message" element
    And I should see a ".ct-field.js-form-item-test-select-5 select option[value='']" element
    And I should see a ".ct-field.js-form-item-test-select-5 select option[value='option_1'][selected='selected']" element
    And I should see a ".ct-field.js-form-item-test-select-5 select option[value='option_2']" element
