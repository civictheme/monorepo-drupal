@civic @paragraph @civic_card_promo
Feature: Tests the Promo card

  Ensure that Promo card paragraph exists and has the expected fields.

  @api
  Scenario: Paragraph type appears in the paragraph types page
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type"
    Then I should see the text "Promo card" in the "civic_card_promo" row

  @api
  Scenario: Content paragraph exists with fields.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/structure/paragraphs_type/civic_card_promo/fields"
    And I should see the text "field_c_p_title" in the "Title" row
    And I should see the text "field_c_p_theme" in the "Theme" row
    And I should see the text "field_c_p_summary" in the "Summary" row
    And I should see the text "field_c_p_link" in the "Link" row
    And I should see the text "field_c_p_image" in the "Image" row
    And I should see the text "field_c_p_date" in the "Date" row


  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' and 'field Banner components' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-1 a" element
    And I click on "div.field--name-field-c-n-banner-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_banner_components_civic_card_container_add_more" button
    And I wait for AJAX to finish
    And I click on "div.field--name-field-c-n-banner-components .field--name-field-c-p-cards .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_banner_components_0_subform_field_c_p_cards_civic_card_promo_add_more" button
    And I wait for AJAX to finish
    And should see an "select[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]'].required" element
    And the option "Light" from select "Theme" is selected
    And should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]'].required" element
    And should see an "div.field--name-field-c-p-image #field_c_p_image-media-library-wrapper-field_c_n_banner_components-0-subform-field_c_p_cards-0-subform" element
    And should see an "div.field--name-field-c-p-image #field_c_p_image-media-library-wrapper-field_c_n_banner_components-0-subform-field_c_p_cards-0-subform.required" element
    And should see an "textarea[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]']" element
    And should see an "textarea[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]'].required" element
    And should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_date][0][value][date]']" element
    And should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]']" element
    And should see an "input[name='field_c_n_banner_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]'].required" element


  @api @javascript
  Scenario: Show relevant fields depending on the 'Content type' and 'field Components' selected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civic_page"
    And I fill in "Title" with "[TEST] Page fields"
    And I click on ".field-group-tabs-wrapper .horizontal-tab-button-2 a" element
    And I click on "div.field--name-field-c-n-components .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_civic_card_container_add_more" button
    And I wait for AJAX to finish
    And I click on "div.field--name-field-c-n-components .field--name-field-c-p-cards .paragraphs-add-wrapper .dropbutton-toggle button" element
    And I wait for AJAX to finish
    And I press the "field_c_n_components_0_subform_field_c_p_cards_civic_card_promo_add_more" button
    And I wait for AJAX to finish
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]']" element
    And should see an "select[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_theme]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_title][0][value]'].required" element
    And should see an "div.field--name-field-c-p-image #field_c_p_image-media-library-wrapper-field_c_n_components-0-subform-field_c_p_cards-0-subform" element
    And should see an "div.field--name-field-c-p-image #field_c_p_image-media-library-wrapper-field_c_n_components-0-subform-field_c_p_cards-0-subform.required" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]']" element
    And should see an "textarea[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_summary][0][value]'].required" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_date][0][value][date]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]']" element
    And should see an "input[name='field_c_n_components[0][subform][field_c_p_cards][0][subform][field_c_p_link][0][uri]'].required" element
