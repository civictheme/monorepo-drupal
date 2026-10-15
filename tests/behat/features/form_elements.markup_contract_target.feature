@skipped @form_contract_target
Feature: Form element markup target contract

  Desired form element markup behaviour. Each scenario documents a defect from
  the form rendering refactor plan's defect register (D1-D15) and is EXPECTED TO FAIL
  until the form rendering refactor lands, at which point the scenario moves
  into the CI profiles. Excluded from CI via @skipped. Run on demand with:
  ahoy test-bdd -- --tags=@form_contract_target

  @api
  Scenario: Controls use valid HTML input types (D2)
    Given I go to "form/civictheme-test-webform-fields"
    Then the page markup should not match "<input[^>]*type=[\x22]entity_autocomplete[\x22]"
    And the page markup should not match "<input[^>]*type=[\x22](webform_[a-z_]+|machine_name|linkit|tableselect)[\x22]"

  @api
  Scenario: Group options do not inherit group-level attributes (D3)
    Given I go to "form/civictheme-test-webform-fields"
    Then the element "#edit-checkboxes input[type=checkbox]" should not have a "data-options-all" attribute
    And the page markup should not match "<input[^>]*class=[\x22][^\x22]*fieldgroup"
    And the page markup should not match "<input[^>]*class=[\x22][^\x22]*form-composite"

  @api
  Scenario: Group option ids follow the core id format (D4)
    Given I go to "form/civictheme-test-webform-fields"
    Then I should see an "#edit-checkboxes-test-option-1" element

  @api
  Scenario: Field descriptions have ids so controls can reference them (D5, D15)
    Given I go to "form/civictheme-test-webform-fields"
    Then the page markup should match "<div[^>]*class=[\x22][^\x22]*ct-field__description[^\x22]*[\x22][^>]* id="

  @api
  Scenario: Error messages are referenced from the control (D5)
    Given I go to "enquiry"
    When I press "Send"
    Then the element "input#edit-first-name" should have an "aria-describedby" attribute equal to "edit-first-name--error-message"
    And the "aria-describedby" attribute of the element "input#edit-first-name" should reference the id of an existing element

  @api
  Scenario: The required marker text is consistent across labels and legends (D8)
    Given I go to "enquiry"
    Then the page markup should not match ">\(Required\)<"

  @api
  Scenario: Submit buttons do not render duplicate attributes (D9)
    Given I go to "enquiry"
    Then the page markup should not match "type=[\x22]submit[\x22][^>]*type="

  @api
  Scenario: Textarea markup is well-formed (D10)
    Given I go to "enquiry"
    Then the page markup should not match "<textarea[^>]*/>"

  @api
  Scenario: File and range controls are themed (D1)
    Given I go to "form/civictheme-test-webform-fields"
    Then I should see the "input#edit-file-upload" element with a "class" attribute containing "ct-"
    And I should see the "input#edit-range" element with a "class" attribute containing "ct-"
