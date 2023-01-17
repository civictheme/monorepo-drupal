@p0 @civictheme @civictheme_theme_settings @civictheme_theme_color_settings
Feature: Color settings are available in the theme settings

  @api
  Scenario: Color fields are present.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme"

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

  @api @javascript
  Scenario: Palette colors have values produced from selected brand colors.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme"
    And I fill color in "#edit-colors-brand-light-brand1" with "#b51a00"
    And I fill color in "#edit-colors-brand-light-brand2" with "#fffc41"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I should see an "#edit-colors-palette-light-background-background[value='#fffc41']" element
    And I should see an "#edit-colors-palette-light-typography-heading[value='#480a00']" element
    And I scroll to an element with id "edit-colors-palette-light-background"

  @api @javascript
  Scenario: Palette colors have values produced from selected brand colors can have overrides.
    Given I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme"
    And I fill color in "#edit-colors-brand-light-brand1" with "#b51a00"
    And I fill color in "#edit-colors-brand-light-brand2" with "#fffc41"
    And I should see an "#edit-colors-palette-light-background-background[value='#fffc41']" element
    And I should see an "#edit-colors-palette-light-typography-heading[value='#480a00']" element
    And I fill color in "#edit-colors-palette-light-background-background-light" with "#000000"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I scroll to an element with id "edit-colors-palette-light-background"
    Then I should see an "#edit-colors-palette-light-background-background-light[value='#000000']" element

  @api @drush @basetheme
  Scenario: The 'css-variables' library CSS file is included on the page when Color Selector is used.
    Given I run drush "config-set civictheme.settings colors.use_color_selector 0"
    And the cache has been cleared
    When I go to the homepage
    Then the response should not contain "/sites/default/files/css-variables.civictheme.css"

    Given I run drush "config-set civictheme.settings colors.use_color_selector 1"
    And the cache has been cleared
    When I go to the homepage
    Then I should see the 'link[href^="/sites/default/files/css-variables.civictheme.css"]' element with the "rel" attribute set to 'stylesheet'

  @api @subtheme
  Scenario: Assert that generating a CSS variable file has different suffix per theme.
    Given I am logged in as a user with the "Site Administrator" role

    And I install "civictheme" theme
    And I set "civictheme" theme as default

    And I visit "/admin/appearance/settings/civictheme"
    And I uncheck the box "Use Color Selector"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    When I go to the homepage
    Then the response should not contain "/sites/default/files/css-variables.civictheme.css"
    And the response should not contain "/sites/default/files/css-variables.civictheme_demo.css"

    When I visit "/admin/appearance/settings/civictheme"
    And I check the box "Use Color Selector"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    Then I should see the 'link[href^="/sites/default/files/css-variables.civictheme.css"]' element with the "rel" attribute set to 'stylesheet'
    And the response should not contain "/sites/default/files/css-variables.civictheme_demo.css"

    When I install "civictheme_demo" theme
    And I set "civictheme_demo" theme as default

    And I visit "/admin/appearance/settings/civictheme_demo"
    And I uncheck the box "Use Color Selector"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    Then the response should not contain "/sites/default/files/css-variables.civictheme.css"
    And the response should not contain "/sites/default/files/css-variables.civictheme_demo.css"

    When I visit "/admin/appearance/settings/civictheme_demo"
    And I check the box "Use Color Selector"
    And I press "Save configuration"
    And I should see the text "The configuration options have been saved."
    And I go to the homepage
    Then the response should not contain "/sites/default/files/css-variables.civictheme.css"
    And I should see the 'link[href^="/sites/default/files/css-variables.civictheme_demo.css"]' element with the "rel" attribute set to 'stylesheet'

  @drush @basetheme
  Scenario: Brand colors can be set through a Drush command.
    Given I run drush 'civictheme:set-brand-colors' '--include=themes/contrib/civictheme/src/Drush "#ff0000" "#00ff00" "#0000ff" "#ffff00" "#00ffff" "#ff00ff"'
    And I run drush 'civictheme:clear-cache' '--include=themes/contrib/civictheme/src/Drush'
    When I go to the homepage
    Then I should see the 'link[href^="/sites/default/files/css-variables.civictheme.css"]' element with the "rel" attribute set to 'stylesheet'
    And save screenshot

    When I go to "/sites/default/files/css-variables.civictheme.css"
    And save screenshot

    And the response should contain "--ct-color-light-heading:#280000;"
    And the response should contain "--ct-color-light-body:#413b3b;"
    And the response should contain "--ct-color-light-background-light:#fcfffc;"
    And the response should contain "--ct-color-light-background:#00ff00;"
    And the response should contain "--ct-color-light-background-dark:#00a300;"
    And the response should contain "--ct-color-light-border-light:#008f00;"
    And the response should contain "--ct-color-light-border:#002800;"
    And the response should contain "--ct-color-light-border-dark:#000200;"
    And the response should contain "--ct-color-light-interaction-text:#f4fff4;"
    And the response should contain "--ct-color-light-interaction-background:#ff0000;"
    And the response should contain "--ct-color-light-interaction-hover-text:#f4fff4;"
    And the response should contain "--ct-color-light-interaction-hover-background:#5b0000;"
    And the response should contain "--ct-color-light-highlight:#0000ff;"
    And the response should contain "--ct-color-dark-heading:#fffffe;"
    And the response should contain "--ct-color-dark-body:#fffff9;"
    And the response should contain "--ct-color-dark-background-light:#18ffff;"
    And the response should contain "--ct-color-dark-background:#00ffff;"
    And the response should contain "--ct-color-dark-background-dark:#007c7c;"
    And the response should contain "--ct-color-dark-border-light:#dfffff;"
    And the response should contain "--ct-color-dark-border:#30ffff;"
    And the response should contain "--ct-color-dark-border-dark:#007c7c;"
    And the response should contain "--ct-color-dark-interaction-text:#00ffff;"
    And the response should contain "--ct-color-dark-interaction-background:#ffff00;"
    And the response should contain "--ct-color-dark-interaction-hover-text:#007c7c;"
    And the response should contain "--ct-color-dark-interaction-hover-background:#ffffa3;"
    And the response should contain "--ct-color-dark-highlight:#ff00ff;"

    When I run drush 'config-set' 'civictheme.settings colors.use_color_selector 0'
    And I run drush 'civictheme:clear-cache' '--include=themes/contrib/civictheme/src/Drush'
    Then the response should not contain "/sites/default/files/css-variables.civictheme.css"
