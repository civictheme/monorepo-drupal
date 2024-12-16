@p1 @civictheme @civictheme_form_updates @civictheme_core_form
Feature: Test a sample of the core form elements

  @api
  Scenario: Fields appear as expected in civictheme
    Given I am an anonymous user
    When I visit "/civictheme-core-form-elements"

    Then I should see "CivicTheme Core Form Elements Form" in the ".ct-banner__title" element

    # Button (button)
    And should see an "[name='op']" element
    And should not see an "[name='op'].required" element
    And should not see an "[name='op'][disabled]" element
    And should see an "[value='Button (button)']" element
    And should see an "[data-component-name='button']" element
    And should see an "[type='submit']" element
    And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular" element
    And I should see an ".button.js-form-submit.form-submit" element

    # Checkbox (checkbox)
    And I should see an "[name='checkbox']" element
    And I should see an "[id='edit-checkbox']" element
    And I should see an ".ct-checkbox.ct-theme-light.ct-field__control.form-checkbox" element

    # Checkboxes (checkboxes)
    And I should see an "[name='checkboxes[option1]']" element
    And I should see an "[id='edit-checkboxes-option1']" element
    And I should see an ".ct-checkbox.ct-theme-light.ct-field__control.form-checkbox" element
    And I should see an "[name='checkboxes[option2]']" element
    And I should see an "[id='edit-checkboxes-option2']" element
    And I should see an ".ct-checkbox.ct-theme-light.ct-field__control.form-checkbox" element
    And I should see an "[name='checkboxes[option3]']" element
    And I should see an "[id='edit-checkboxes-option3']" element
    And I should see an ".ct-checkbox.ct-theme-light.ct-field__control.form-checkbox" element

    # Date (date)
    And I should see an "[name='date']" element
    And I should see an "[id='edit-date']" element
    And I should see an ".form-date.form-element.form-element--type-date.form-element--api-date.form-control" element

    # Datelist (datelist)
    And I should see an "[name='datelist[year]']" element
    And I should see an "[id='edit-datelist-year']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element
    And I should see an "[name='datelist[month]']" element
    And I should see an "[id='edit-datelist-month']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element
    And I should see an "[name='datelist[day]']" element
    And I should see an "[id='edit-datelist-day']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element
    And I should see an "[name='datelist[hour]']" element
    And I should see an "[id='edit-datelist-hour']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element
    And I should see an "[name='datelist[minute]']" element
    And I should see an "[id='edit-datelist-minute']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element

    # Datetime (datetime)
    And I should see an "[name='datetime[date]']" element
    And I should see an "[id='edit-datetime-date']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-date" element
    And I should see an "[name='datetime[time]']" element
    And I should see an "[id='edit-datetime-time']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-time" element

    # Email (email)
    And I should see an "[name='email']" element
    And I should see an "[id='edit-email']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-email" element
    And I should see an ".form-email.form-element.form-element--type-email.form-element--api-email.form-control" element

    # Entity Autocomplete (entity_autocomplete)
    And I should see an "[name='entity_autocomplete']" element
    And I should see an "[id='edit-entity-autocomplete']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-autocomplete" element

    # File (file)
    And I should see an "[name='files[file]']" element
    And I should see an "[id='edit-file']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-file" element

    # Image Button (image_button)
    And I should see an "[name='op'][type='image']" element
    And I should see an "[id='edit-1--2']" element
    And I should see an "[src='/themes/contrib/civictheme/assets/icons/download.svg']" element

    # Linkit (linkit)
    And I should see an "[name='linkit']" element
    And I should see an "[id='edit-linkit']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-text" element

    # Machine Name (machine_name)
    And I should see an "[name='machine_name']" element
    And I should see an "[id='edit-machine-name']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-text" element

    # Number (number)
    And I should see an "[name='number']" element
    And I should see an "[id='edit-number']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-number" element
    And I should see an ".form-number.form-element.form-element--type-number.form-element--api-number.form-control" element

    # Password (password)
    And I should see an "[name='password']" element
    And I should see an "[id='edit-password']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-text" element

    # Password Confirm (password_confirm)
    And I should see an "[name='password_confirm[pass1]']" element
    And I should see an "[id='edit-password-confirm-pass1']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-text" element
    And I should see an "[name='password_confirm[pass2]']" element
    And I should see an "[id='edit-password-confirm-pass2']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-text" element
    And I should see an ".form-text.form-element.form-element--type-password.form-element--api-password.form-control" element
    And I should see an ".password-confirm.js-password-confirm" element

    # Path (path)
    And I should see an "[name='path']" element
    And I should see an "[id='edit-path']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-text" element

    # Radio (radio)
    And I should see an "[name='radio']" element
    And I should see an "[id='edit-radio']" element
    And I should see an ".ct-radio.ct-theme-light.ct-field__control.form-radio" element

    # Radios (radios)
    And I should see an "[name='radios']" element
    And I should see an "[id='edit-radios-option1']" element
    And I should see an ".ct-radio.ct-theme-light.ct-field__control.form-radio" element
    And I should see an "[name='radios']" element
    And I should see an "[id='edit-radios-option2']" element
    And I should see an ".ct-radio.ct-theme-light.ct-field__control.form-radio" element
    And I should see an "[name='radios']" element
    And I should see an "[id='edit-radios-option3']" element
    And I should see an ".ct-radio.ct-theme-light.ct-field__control.form-radio" element

    # Range (range)
    And I should see an "[name='range']" element
    And I should see an "[id='edit-range']" element
    And I should not see an ".ct-input.ct-theme-light.ct-field__control.form-range" element

    # Search (search)
    And I should see an "[name='search']" element
    And I should see an "[id='edit-search']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-search" element

    # Select (select)
    And I should not see an "[name='select']" element
    And I should not see an "[id='edit-select']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element

    # Submit (submit)
    And I should see an "[name='op'][type='submit']" element
    And I should see an "[id='edit-1--3']" element
    And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit" element

    # Table (table)
    And I should see an "[id='edit-table']" element
    And I should see an ".ct-table.ct-theme-light.ct-table--caption-before" element

    # Tel (tel)
    And I should see an "[name='tel']" element
    And I should see an "[id='edit-tel']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-tel" element
    And I should see an ".form-tel.form-element.form-element--type-tel.form-element--api-tel.form-control" element

    # Textarea (textarea)
    And I should see an "[name='textarea']" element
    And I should see an "[id='edit-textarea']" element
    And I should see an ".ct-textarea.ct-theme-light.ct-field__control.form-element" element

    # Textfield (textfield)
    And I should see an "[name='textfield']" element
    And I should see an "[id='edit-textfield']" element
    And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text" element

    # Url (url)
    And I should see an "[name='url']" element
    And I should see an "[id='edit-url']" element
    And I should see an ".ct-input.ct-theme-light.ct-field__control.form-url" element
    And I should see an ".form-url.form-element.form-element--type-url.form-element--api-url.form-control" element

    # Weight (weight)
    And I should see an "[name='weight']" element
    And I should see an "[id='edit-weight']" element
    And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select" element

    # Markup within a form
    And I should see "Markup within a form" in the "h2" element
    And I should see "Test content 1" in the "p" element
    And I should see an ".ct-basic-content.ct-theme-light ul li" element
    And I should see the text "List Item 1"
    And I should see the text "List Item 2"
    And I should see the text "List Item 3"
    And I should see an ".ct-link" element
    And I should see the link "Examples" with "https://www.drupal.org/docs/8/api/form-api/examples"

  @api
  Scenario: Fields appear as expected in admin theme
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "/admin/structure/civictheme-core-form-elements"

    Then I should see "Core Form Elements Form (Admin Theme)" in the ".page-title" element

    # Button (button)
    And should see an "[name='op']" element
    And should not see an "[name='op'].required" element
    And should not see an "[name='op'][disabled]" element
    And should see an "[value='Button (button)']" element
    And should not see an "[data-component-name='button']" element
    And should see an "[type='submit']" element
    And I should not see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular" element
    And I should see an ".button.js-form-submit.form-submit" element

    # Checkbox (checkbox)
    And I should see an "[name='checkbox']" element
    And I should see an "[id='edit-checkbox']" element
    And I should see an ".form-checkbox.form-boolean.form-boolean--type-checkbox" element

    # Checkboxes (checkboxes)
    And I should see an "[name='checkboxes[option1]']" element
    And I should see an "[id='edit-checkboxes-option1']" element
    And I should see an ".form-checkbox.form-boolean.form-boolean--type-checkbox" element
    And I should see an "[name='checkboxes[option2]']" element
    And I should see an "[id='edit-checkboxes-option2']" element
    And I should see an ".form-checkbox.form-boolean.form-boolean--type-checkbox" element
    And I should see an "[name='checkboxes[option3]']" element
    And I should see an "[id='edit-checkboxes-option3']" element
    And I should see an ".form-checkbox.form-boolean.form-boolean--type-checkbox" element

    # Datelist (datelist)
    And I should see an "[name='datelist[year]']" element
    And I should see an "[id='edit-datelist-year']" element
    And I should see an ".form-select.form-element.form-element--type-select" element
    And I should see an "[name='datelist[month]']" element
    And I should see an "[id='edit-datelist-month']" element
    And I should see an ".form-select.form-element.form-element--type-select" element
    And I should see an "[name='datelist[day]']" element
    And I should see an "[id='edit-datelist-day']" element
    And I should see an ".form-select.form-element.form-element--type-select" element
    And I should see an "[name='datelist[hour]']" element
    And I should see an "[id='edit-datelist-hour']" element
    And I should see an ".form-select.form-element.form-element--type-select" element
    And I should see an "[name='datelist[minute]']" element
    And I should see an "[id='edit-datelist-minute']" element
    And I should see an ".form-select.form-element.form-element--type-select" element

    # Datetime (datetime)
    And I should see an "[name='datetime[date]']" element
    And I should see an "[id='edit-datetime-date']" element
    And I should see an ".form-date.form-element.form-element--type-date" element
    And I should see an "[name='datetime[time]']" element
    And I should see an "[id='edit-datetime-time']" element
    And I should see an ".form-time.form-element.form-element--type-time" element

    # Email (email)
    And I should see an "[name='email']" element
    And I should see an "[id='edit-email']" element
    And I should see an ".form-email.form-element.form-element--type-email" element

    # Entity Autocomplete (entity_autocomplete)
    And I should see an "[name='entity_autocomplete']" element
    And I should see an "[id='edit-entity-autocomplete']" element
    And I should see an ".form-autocomplete.form-text.form-element.form-element--type-text" element

    # File (file)
    And I should see an "[name='files[file]']" element
    And I should see an "[id='edit-file']" element
    And I should see an ".form-file.form-element.form-element--type-file" element

    # Image Button (image_button)
    And I should see an "[name='op'][type='image']" element
    And I should see an "[id='edit-1--2']" element
    And I should see an "[src='/themes/contrib/civictheme/assets/icons/download.svg']" element

    # Linkit (linkit)
    And I should see an "[name='linkit']" element
    And I should see an "[id='edit-linkit']" element
    And I should see an ".form-text.form-element.form-element--type-text" element

    # Machine Name (machine_name)
    And I should see an "[name='machine_name']" element
    And I should see an "[id='edit-machine-name']" element
    And I should see an ".form-text.required.form-element.form-element--type-text" element

    # Number (number)
    And I should see an "[name='number']" element
    And I should see an "[id='edit-number']" element
    And I should see an ".form-number.form-element.form-element--type-number" element

    # Password (password)
    And I should see an "[name='password']" element
    And I should see an "[id='edit-password']" element
    And I should see an ".form-text.form-element.form-element--type-password" element

    # Password Confirm (password_confirm)
    And I should see an "[name='password_confirm[pass1]']" element
    And I should see an "[id='edit-password-confirm-pass1']" element
    And I should see an ".form-text.form-element.form-element--type-password" element
    And I should see an "[name='password_confirm[pass2]']" element
    And I should see an "[id='edit-password-confirm-pass2']" element
    And I should see an ".form-text.form-element.form-element--type-password" element

    # Path (path)
    And I should see an "[name='path']" element
    And I should see an "[id='edit-path']" element
    And I should see an ".form-text.form-element.form-element--type-text" element

    # Radio (radio)
    And I should see an "[name='radio']" element
    And I should see an "[id='edit-radio']" element
    And I should see an ".form-radio.form-boolean.form-boolean--type-radio" element

    # Radios (radios)
    And I should see an "[name='radios']" element
    And I should see an "[id='edit-radios-option1']" element
    And I should see an ".form-radio.form-boolean.form-boolean--type-radio" element
    And I should see an "[name='radios']" element
    And I should see an "[id='edit-radios-option2']" element
    And I should see an ".form-radio.form-boolean.form-boolean--type-radio" element
    And I should see an "[name='radios']" element
    And I should see an "[id='edit-radios-option3']" element
    And I should see an ".form-radio.form-boolean.form-boolean--type-radio" element

    # Range (range)
    And I should see an "[name='range']" element
    And I should see an "[id='edit-range']" element
    And I should see an ".form-range" element

    # Search (search)
    And I should see an "[name='search']" element
    And I should see an "[id='edit-search']" element
    And I should see an ".form-search.form-element.form-element--type-search" element

    # Select (select)
    And I should see an "[name='select']" element
    And I should see an "[id='edit-select']" element
    And I should see an ".form-select.form-element.form-element--type-select" element

    # Submit (submit)
    And I should see an "[name='op'][type='submit']" element
    And I should see an "[id='edit-1--3']" element
    And I should see an ".button.js-form-submit.form-submit" element

    # Table (table)
    And I should see an "[id='edit-table']" element
    And I should see an ".responsive-enabled" element

    # Tel (tel)
    And I should see an "[name='tel']" element
    And I should see an "[id='edit-tel']" element
    And I should see an ".form-tel.form-element.form-element--type-tel" element

    # Textarea (textarea)
    And I should see an "[name='textarea']" element
    And I should see an "[id='edit-textarea']" element
    And I should see an ".form-textarea.resize-vertical.form-element.form-element--type-textarea" element

    # Textfield (textfield)
    And I should see an "[name='textfield']" element
    And I should see an "[id='edit-textfield']" element
    And I should see an ".form-text.form-element.form-element--type-text" element

    # Url (url)
    And I should see an "[name='url']" element
    And I should see an "[id='edit-url']" element
    And I should see an ".form-url.form-element.form-element--type-url" element

    # Weight (weight)
    And I should see an "[name='weight']" element
    And I should see an "[id='edit-weight']" element
    And I should see an ".form-select.form-element.form-element--type-select" element

    # Markup within a form
    And I should see the text "Markup within a form"
    And I should see the text "Test content 1"
    And I should see the text "List Item 1"
    And I should see the text "List Item 2"
    And I should see the text "List Item 3"
    And I should see the link "Examples" with "https://www.drupal.org/docs/8/api/form-api/examples"
