@p0 @civictheme @media_type
Feature: Media tags field

  Background:
    Given I am logged in as a user with the "Site Administrator" role

  @api
  Scenario Outline: Fields appear as expected
    When I go to "media/add/<media_type>"
    Then I should see an "[name='name[0][value]']" element
    And I should see an "[name='name[0][value]'].required" element
    And I should see an "[name='field_c_m_media_tags[0][target_id]']" element

    Examples:
      | media_type              |
      | civictheme_audio        |
      | civictheme_document     |
      | civictheme_icon         |
      | civictheme_image        |
      | civictheme_remote_video |
      | civictheme_video        |
