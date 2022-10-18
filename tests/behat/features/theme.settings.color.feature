@civictheme @civictheme_theme_settings
Feature: Check that Color settings are available in theme settings

  @api
  Scenario: The CivicTheme theme color settings.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"

    And I should see an "input[name='colors[use_brand_colors]']" element

    # All Light then all Dark.
    And I should see the text "Light"

    And I should see the text "Brand colors"
    And I should see the text "Brand1"
    And I should see an "input[name='colors[brand][light][brand1]']" element
    And I should see the text "Brand2"
    And I should see an "input[name='colors[brand][light][brand2]']" element
    And I should see the text "Brand3"
    And I should see an "input[name='colors[brand][light][brand3]']" element
    And I should see the text "Brand1"
    And I should see an "input[name='colors[brand][dark][brand1]']" element
    And I should see the text "Brand2"
    And I should see an "input[name='colors[brand][dark][brand2]']" element
    And I should see the text "Brand3"
    And I should see an "input[name='colors[brand][dark][brand3]']" element

    And I should see the text "Palette colors"

    And I should see the text "Typography"
    And I should see the text "Heading"
    And I should see an "input[name='colors[palette][light][typography][heading]']" element
    And I should see the text "Body"
    And I should see an "input[name='colors[palette][light][typography][body]']" element

    And I should see the text "Background"
    And I should see the text "Background light"
    And I should see an "input[name='colors[palette][light][background][background_light]']" element
    And I should see the text "Background"
    And I should see an "input[name='colors[palette][light][background][background]']" element
    And I should see the text "Background dark"
    And I should see an "input[name='colors[palette][light][background][background_dark]']" element

    And I should see the text "Border"
    And I should see the text "Border light"
    And I should see an "input[name='colors[palette][light][border][border_light]']" element
    And I should see the text "Border"
    And I should see an "input[name='colors[palette][light][border][border]']" element
    And I should see the text "Border dark"
    And I should see an "input[name='colors[palette][light][border][border_dark]']" element

    And I should see the text "Interaction"
    And I should see the text "Interaction text"
    And I should see an "input[name='colors[palette][light][interaction][interaction_text]']" element
    And I should see the text "Interaction background"
    And I should see an "input[name='colors[palette][light][interaction][interaction_background]']" element
    And I should see the text "Interaction hover text"
    And I should see an "input[name='colors[palette][light][interaction][interaction_hover_text]']" element
    And I should see the text "Interaction hover background"
    And I should see an "input[name='colors[palette][light][interaction][interaction_hover_background]']" element
    And I should see the text "Interaction focus"
    And I should see an "input[name='colors[palette][light][interaction][interaction_focus]']" element

    And I should see the text "Highlight"
    And I should see the text "Highlight"
    And I should see an "input[name='colors[palette][light][highlight][highlight]']" element

    And I should see the text "Status"
    And I should see the text "Information"
    And I should see an "input[name='colors[palette][light][status][information]']" element
    And I should see the text "Warning"
    And I should see an "input[name='colors[palette][light][status][warning]']" element
    And I should see the text "Error"
    And I should see an "input[name='colors[palette][light][status][error]']" element
    And I should see the text "Success"
    And I should see an "input[name='colors[palette][light][status][success]']" element


    And I should see the text "Dark"

    And I should see the text "Brand colors"
    And I should see the text "Brand1"
    And I should see an "input[name='colors[brand][dark][brand1]']" element
    And I should see the text "Brand2"
    And I should see an "input[name='colors[brand][dark][brand2]']" element
    And I should see the text "Brand3"
    And I should see an "input[name='colors[brand][dark][brand3]']" element
    And I should see the text "Brand1"
    And I should see an "input[name='colors[brand][dark][brand1]']" element
    And I should see the text "Brand2"
    And I should see an "input[name='colors[brand][dark][brand2]']" element
    And I should see the text "Brand3"
    And I should see an "input[name='colors[brand][dark][brand3]']" element

    And I should see the text "Palette colors"

    And I should see the text "Typography"
    And I should see the text "Heading"
    And I should see an "input[name='colors[palette][dark][typography][heading]']" element
    And I should see the text "Body"
    And I should see an "input[name='colors[palette][dark][typography][body]']" element

    And I should see the text "Background"
    And I should see the text "Background light"
    And I should see an "input[name='colors[palette][dark][background][background_light]']" element
    And I should see the text "Background"
    And I should see an "input[name='colors[palette][dark][background][background]']" element
    And I should see the text "Background dark"
    And I should see an "input[name='colors[palette][dark][background][background_dark]']" element

    And I should see the text "Border"
    And I should see the text "Border light"
    And I should see an "input[name='colors[palette][dark][border][border_light]']" element
    And I should see the text "Border"
    And I should see an "input[name='colors[palette][dark][border][border]']" element
    And I should see the text "Border dark"
    And I should see an "input[name='colors[palette][dark][border][border_dark]']" element

    And I should see the text "Interaction"
    And I should see the text "Interaction text"
    And I should see an "input[name='colors[palette][dark][interaction][interaction_text]']" element
    And I should see the text "Interaction background"
    And I should see an "input[name='colors[palette][dark][interaction][interaction_background]']" element
    And I should see the text "Interaction hover text"
    And I should see an "input[name='colors[palette][dark][interaction][interaction_hover_text]']" element
    And I should see the text "Interaction hover background"
    And I should see an "input[name='colors[palette][dark][interaction][interaction_hover_background]']" element
    And I should see the text "Interaction focus"
    And I should see an "input[name='colors[palette][dark][interaction][interaction_focus]']" element

    And I should see the text "Highlight"
    And I should see the text "Highlight"
    And I should see an "input[name='colors[palette][dark][highlight][highlight]']" element

    And I should see the text "Status"
    And I should see the text "Information"
    And I should see an "input[name='colors[palette][dark][status][information]']" element
    And I should see the text "Warning"
    And I should see an "input[name='colors[palette][dark][status][warning]']" element
    And I should see the text "Error"
    And I should see an "input[name='colors[palette][dark][status][error]']" element
    And I should see the text "Success"
    And I should see an "input[name='colors[palette][dark][status][success]']" element

    And I press "Save configuration"
    Then I should see the text "The configuration options have been saved."

  # Drush driver does not support passing '--include', this test is skipped until patch provided.
  @drush @skipped
  Scenario: The CivicTheme theme color settings can be set through a Drush command
    Given I run drush 'civictheme:set-brand-colors' '--include=docroot/themes/contrib/civictheme/src/Drush "#ff0000" "#ff0000" "#ff0000" "#ff0000" "#ff0000" "#ff0000"'
    When I go to the homepage
    Then save screenshot

    Given I run drush 'civictheme:set-brand-colors' '--include=docroot/themes/contrib/civictheme/src/Drush "#00ff00" "#00ff00" "#00ff00" "#00ff00" "#00ff00" "#00ff00"'
    When I go to the homepage
    Then save screenshot

    Given I run drush 'civictheme:set-brand-colors' '--include=docroot/themes/contrib/civictheme/src/Drush "#0000ff" "#0000ff" "#0000ff" "#0000ff" "#0000ff" "#0000ff"'
    When I go to the homepage
    Then save screenshot

    Given I run drush 'civictheme:set-brand-colors' '--include=docroot/themes/contrib/civictheme/src/Drush "#00698f" "#e6e9eb" "#121313" "#61daff" "#003a4f" "#00698f"'
    When I go to the homepage
    Then save screenshot
