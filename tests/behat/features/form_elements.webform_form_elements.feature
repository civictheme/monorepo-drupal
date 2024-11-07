@p1 @civictheme @civictheme_form_updates @civictheme_webform
Feature: Test a sample of the webform elements in the webform

    @api
    Scenario: Fields appear as expected
        Given I am an anonymous user
        When I visit "form/civictheme-test-webform-fields"

        Then I should see "Civictheme Test Webform - Fields" in the ".ct-banner__title" element

        # Checkbox (checkbox)
        And I should see an "[name='checkbox']" element
        And I should see an "[id='edit-checkbox']" element
        And I should see an ".ct-checkbox.ct-theme-light.ct-field__control" element
        And I should see an ".form-checkbox" element

        # Text field (textfield)
        And I should see an "[name='text_field']" element
        And I should see an "[id='edit-text-field']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control" element
        And I should see an ".form-text" element

        # Radio buttons (select_to_display_textarea)
        And I should see an "[name='select_to_display_textarea']" element
        And I should see an "[id='edit-select-to-display-textarea-Display-textarea']" element
        And I should see an "[id='edit-select-to-display-textarea-Do-not-display-textarea']" element
        And I should see an ".ct-radio.ct-theme-light.ct-field__control" element

        # Textarea (textarea)
        And I should see an "[name='textarea']" element
        And I should see an "[id='edit-textarea']" element
        And I should see an ".ct-textarea.ct-theme-light.ct-field__control" element

        # Checkbox to display textfield (checkbox_visible_text_field_1)
        And I should see an "[name='checkbox_visible_text_field_1']" element
        And I should see an "[id='edit-checkbox-visible-text-field-1']" element
        And I should see an ".ct-checkbox.ct-theme-light.ct-field__control.form-checkbox" element

        # Text field 1 (text_field_1)
        And I should see an "[name='text_field_1']" element
        And I should see an "[id='edit-text-field-1']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text" element

        # Autocomplete (autocomplete)
        And I should see an "[name='autocomplete']" element
        And I should see an "[id='edit-autocomplete']" element
        And I should see an ".js-form-item.form-item.js-form-type-webform-autocomplete.form-item-autocomplete.js-form-item-autocomplete" element

        # Color (color)
        And I should see an "[name='color']" element
        And I should see an "[id='edit-color']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--color.ct-field--vertical.form-item.js-form-item.js-form-type-color.form-type-color.form-type--color.js-form-item-color.form-item-color.form-item--color" element

        # Email (email)
        And I should see an "[name='email']" element
        And I should see an "[id='edit-email']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--email.ct-field--vertical.form-item.js-form-item.js-form-type-email.form-type-email.form-type--email.js-form-item-email.form-item-email.form-item--email" element

        # Email confirm (email_confirm)
        And I should see an "[name='email_confirm[mail_1]']" element
        And I should see an "[id='edit-email-confirm-mail-1']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--email.ct-field--vertical.form-item.js-form-item.js-form-type-email.form-type-email.form-type--email.js-form-item-email-confirm-mail-1.form-item-email-confirm-mail-1.form-item--email-confirm-mail-1" element
        And I should see an "[name='email_confirm[mail_2]']" element
        And I should see an "[id='edit-email-confirm-mail-2']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--email.ct-field--vertical.form-item.js-form-item.js-form-type-email.form-type-email.form-type--email.js-form-item-email-confirm-mail-2.form-item-email-confirm-mail-2.form-item--email-confirm-mail-2" element

        # Email multiple (email_multiple)
        And I should see an "[name='email_multiple']" element
        And I should see an "[id='edit-email-multiple']" element
        And I should see an ".js-form-item.form-item.js-form-type-webform-email-multiple.form-item-email-multiple.js-form-item-email-multiple" element

        # Number (number)
        And I should see an "[name='number']" element
        And I should see an "[id='edit-number']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--number.ct-field--vertical.form-item.js-form-item.js-form-type-number.form-type-number.form-type--number.js-form-item-number.form-item-number.form-item--number" element

        # Number - Max 10 - Min 4 (number_max_10_min_4)
        And I should see an "[name='number_max_10_min_4']" element
        And I should see an "[id='edit-number-max-10-min-4']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--number.ct-field--vertical.form-item.js-form-item.js-form-type-number.form-type-number.form-type--number.js-form-item-number-max-10-min-4.form-item-number-max-10-min-4.form-item--number-max-10-min-4" element

        # Range (range)
        And I should see an "[name='range']" element
        And I should see an "[id='edit-range']" element
        And I should see an ".js-form-item.form-item.js-form-type-range.form-item-range.js-form-item-range" element

        # Range Min 10 Max 100 Steps 10 (range_min_10_max_100_steps_10)
        And I should see an "[name='range_min_10_max_100_steps_10']" element
        And I should see an "[id='edit-range-min-10-max-100-steps-10']" element
        And I should see an ".js-form-item.form-item.js-form-type-range.form-item-range-min-10-max-100-steps-10.js-form-item-range-min-10-max-100-steps-10" element

        # Rating (rating)
        And I should see an "[name='rating']" element
        And I should see an "[id='edit-rating']" element
        And I should see an ".js-form-item.form-item.js-form-type-webform-rating.form-item-rating.js-form-item-rating" element

        # Telephone (telephone)
        And I should see an "[name='telephone']" element
        And I should see an "[id='edit-telephone']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--tel.ct-field--vertical.form-item.js-form-item.js-form-type-tel.form-type-tel.form-type--tel.js-form-item-telephone.form-item-telephone.form-item--telephone" element

        # Terms of service (terms_of_service)
        And I should see an "[name='terms_of_service']" element
        And I should see an "[id='edit-terms-of-service']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--checkbox.ct-field--vertical.form-type-webform-terms-of-service.js-form-type-webform-terms-of-service.form-item.js-form-item.js-form-type-checkbox.form-type-checkbox.form-type--checkbox.js-form-item-terms-of-service.form-item-terms-of-service.form-item--terms-of-service" element

        # URL (url)
        And I should see an "[name='url']" element
        And I should see an "[id='edit-url']" element
        And I should see an ".ct-field.ct-theme-light.ct-field--url.ct-field--vertical.form-item.js-form-item.js-form-type-url.form-type-url.form-type--url.js-form-item-url.form-item-url.form-item--url" element

        # Address (address)
        And I should see an "[name='address[address]']" element
        And I should see an "[id='edit-address-address']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='address[address_2]']" element
        And I should see an "[id='edit-address-address-2']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='address[city]']" element
        And I should see an "[id='edit-address-city']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='address[state_province]']" element
        And I should see an "[id='edit-address-state-province']" element
        And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select.form-element.form-element--type-select.form-element--api-select.form-control" element

        And I should see an "[name='address[postal_code]']" element
        And I should see an "[id='edit-address-postal-code']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='address[country]']" element
        And I should see an "[id='edit-address-country']" element
        And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select.form-element.form-element--type-select.form-element--api-select.form-control" element

        # Contact (contact)
        And I should see an "[name='contact[name]']" element
        And I should see an "[id='edit-contact-name']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='contact[company]']" element
        And I should see an "[id='edit-contact-company']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='contact[email]']" element
        And I should see an "[id='edit-contact-email']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-email.form-element.form-element--type-email.form-element--api-email.form-control" element

        And I should see an "[name='contact[phone]']" element
        And I should see an "[id='edit-contact-phone']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-tel.form-element.form-element--type-tel.form-element--api-tel.form-control" element

        And I should see an "[name='contact[address]']" element
        And I should see an "[id='edit-contact-address']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='contact[address_2]']" element
        And I should see an "[id='edit-contact-address-2']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='contact[city]']" element
        And I should see an "[id='edit-contact-city']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='contact[state_province]']" element
        And I should see an "[id='edit-contact-state-province']" element
        And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select.form-element.form-element--type-select.form-element--api-select.form-control" element

        And I should see an "[name='contact[postal_code]']" element
        And I should see an "[id='edit-contact-postal-code']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='contact[country]']" element
        And I should see an "[id='edit-contact-country']" element
        And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select.form-element.form-element--type-select.form-element--api-select.form-control" element

        # Link (link)
        And I should see an "[name='link[title]']" element
        And I should see an "[id='edit-link-title']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='link[url]']" element
        And I should see an "[id='edit-link-url']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-url.form-element.form-element--type-url.form-element--api-url.form-control" element

        # Name (name)
        And I should see an "[name='name[title][select]']" element
        And I should see an "[id='edit-name-title-select']" element
        And I should see an ".ct-select.ct-theme-light.ct-field__control.form-element.form-element--type-select.form-element--api-select.form-control" element

        And I should see an "[name='name[first]']" element
        And I should see an "[id='edit-name-first']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='name[middle]']" element
        And I should see an "[id='edit-name-middle']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='name[last]']" element
        And I should see an "[id='edit-name-last']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='name[suffix]']" element
        And I should see an "[id='edit-name-suffix']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        And I should see an "[name='name[degree]']" element
        And I should see an "[id='edit-name-degree']" element
        And I should see an ".ct-textfield.ct-theme-light.ct-field__control.form-text.form-element.form-element--type-textfield.form-element--api-textfield.form-control" element

        # Telephone advanced (telephone_advanced)
        And I should see an "[name='telephone_advanced[type]']" element
        And I should see an "[id='edit-telephone-advanced-type']" element
        And I should see an ".ct-select.ct-theme-light.ct-field__control.form-select.form-element.form-element--type-select.form-element--api-select.form-control" element

        And I should see an "[name='telephone_advanced[phone]']" element
        And I should see an "[id='edit-telephone-advanced-phone']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.js-webform-telephone-international.webform-webform-telephone-international.form-tel.form-element.form-element--type-tel.form-element--api-tel.form-control" element

        And I should see an "[name='telephone_advanced[ext]']" element
        And I should see an "[id='edit-telephone-advanced-ext']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-number.form-element.form-element--type-number.form-element--api-number.form-control" element
        # Audio file
        And I should see an "[name='files[audio_file]']" element
        And I should see an "[id='edit-audio-file-upload']" element
        And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular.js-hide.button.js-form-submit.form-submit" element

        # Document file
        And I should see an "[name='files[document_file]']" element
        And I should see an "[id='edit-document-file-upload']" element
        And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular.js-hide.button.js-form-submit.form-submit" element

        # File
        And I should see an "[name='files[file]']" element
        And I should see an "[id='edit-file-upload']" element
        And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular.js-hide.button.js-form-submit.form-submit" element

        # Image file
        And I should see an "[name='files[image_file]']" element
        And I should see an "[id='edit-image-file-upload']" element
        And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular.js-hide.button.js-form-submit.form-submit" element

        # Video file
        And I should see an "[name='files[video_file]']" element
        And I should see an "[id='edit-video-file-upload']" element
        And I should see an ".ct-button.ct-theme-light.ct-button--primary.ct-button--submit.ct-button--regular.js-hide.button.js-form-submit.form-submit" element
        # Date (date)
        And I should see an "[name='date']" element
        And I should see an "[id='edit-date']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-date" element

        # Date/time (date_time)
        And I should see an "[name='date_time[date]']" element
        And I should see an "[id='edit-date-time-date']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-date" element
        And I should see an "[name='date_time[time]']" element
        And I should see an "[id='edit-date-time-time']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control.form-time" element

        # Entity autocomplete (entity_autocomplete)
        And I should see an "[name='entity_autocomplete']" element
        And I should see an "[id='edit-entity-autocomplete']" element
        And I should see an ".ct-input.ct-theme-light.ct-field__control" element
        And I should see an ".form-autocomplete.form-text.form-element.form-element--type-entity_autocomplete.form-element--api-entity_autocomplete.form-control" element
