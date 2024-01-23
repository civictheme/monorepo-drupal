@p0 @civictheme @civictheme_media
Feature: CivicTheme media renders on views pages with filters

  Ensure that CivicTheme media has exposed filters as expected.

  Background:
    Given "civictheme_media_tags" terms:
      | name         |
      | [TEST] Tag 1 |
      | [TEST] Tag 2 |
    And managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
    And "civictheme_image" media:
      | name                      | field_c_m_image | field_c_m_media_tags       |
      | [TEST] CivicTheme Image 1 | test_image.jpg  | [TEST] Tag 1               |
      | [TEST] CivicTheme Image 2 | test_image.jpg  | [TEST] Tag 1, [TEST] Tag 2 |
    
  @api
  Scenario: Color fields are present.
    Given I am logged in as a user with the "Site Administrator" role
    When I go to "admin/content/media"
    Then the response status code should be 200

    And I fill in the following:
      | field_c_m_media_tags_target_id | [TEST] Tag 1, [TEST] Tag 2 |
    And I press "Filter"

    Then I should see the text "[TEST] CivicTheme Image 1"
    And I should see the text "[TEST] CivicTheme Image 2"

    And I fill in the following:
      | field_c_m_media_tags_target_id | [TEST] Tag 2 |
    And I press "Filter"

    Then I should not see the text "[TEST] CivicTheme Image 1"
    And I should see the text "[TEST] CivicTheme Image 2"
