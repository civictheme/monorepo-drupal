@p1 @civictheme @civictheme_migrate
Feature: CivicTheme migrate module settings

  @api @javascript @drush
  Scenario: Settings form works correctly
    # Reset settings.
    Given I run drush "config-set civictheme_migrate.settings remote_authentication.type none"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.username ''"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.password ''"

    Given I am logged in as a user with the "administrator" role
    When I go to "admin/config/civictheme-migrate"

    # Assert default state.
    Then the "remote_authentication[type]" field should contain "none"
    And I should not see a visible "#edit-remote-authentication-basic" element
    And I should not see a visible "[name='remote_authentication[basic][username]']" element
    And I should not see a visible "[name='remote_authentication[basic][password]']" element

    # Assert the state preserved after save.
    When I press "Save configuration"
    Then the "remote_authentication[type]" field should contain "none"
    And I should not see a visible "#edit-remote-authentication-basic" element
    And I should not see a visible "[name='remote_authentication[basic][username]']" element
    And I should not see a visible "[name='remote_authentication[basic][password]']" element

    # Assert different authentication selected.
    When I select the radio button "Basic authentication"
    Then I should see a visible "#edit-remote-authentication-basic" element
    And I should see a visible "[name='remote_authentication[basic][username]']" element
    And I should see a visible "[name='remote_authentication[basic][password]']" element

    When I fill in "Username" with "testusername"
    And I fill in "Password" with "testpassword"
    And I press "Save configuration"
    Then I should see a visible "#edit-remote-authentication-basic" element
    And I should see a visible "[name='remote_authentication[basic][username]']" element
    And I should see a visible "[name='remote_authentication[basic][password]']" element
    And the "remote_authentication[basic][username]" field should contain "testusername"
    And the "remote_authentication[basic][password]" field should contain "testpassword"

    # Reset settings.
    Given I run drush "config-set civictheme_migrate.settings remote_authentication.type none"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.username ''"
    And I run drush "config-set civictheme_migrate.settings remote_authentication.basic.password ''"

  @api
  Scenario: Schemas appear on the settings page

    Given I am logged in as a user with the "administrator" role
    When I go to "admin/config/civictheme-migrate"

    Then I see the text "Migration schemas"
    And I see the text "media_civictheme_image"
    And I see the text "node_civictheme_page"
    And I see the text '"$schema": "https:'
