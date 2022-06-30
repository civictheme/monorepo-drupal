@civictheme @media @civictheme_remote_video
Feature: Tests the media types civictheme remote video

  Ensure that remote video is rendered correctly when embedded into WYSIWYG.

  Background:
    Given "civictheme_remote_video" media:
      | name                           | field_c_m_oembed_video                      | field_c_m_transcript                      | uuid                                 |
      | [TEST] CivicTheme Remote Video | https://www.youtube.com/watch?v=C0DPdy98e4c | [TEST] CivicTheme Remote Video Transcript | 5bca9d25-2f72-4c41-9e89-a5d48dc1cd23 |
    And "civictheme_page" content:
      | title                                    | status |
      | [TEST] CivicTheme Video and Remote Video | 1      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] CivicTheme Video and Remote Video" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <h2>Test Video</h2> <h2>Test Remote Video</h2><br /><drupal-media data-align="center" data-entity-type="media" data-entity-uuid="5bca9d25-2f72-4c41-9e89-a5d48dc1cd23"></drupal-media> |
      | field_c_p_content:format | civictheme_rich_text                                                                                                                                                                   |

  @api @javascript @civictheme_remote_video
  Scenario: Ensure that a remote video renders correctly and links correctly to transcript when added to WYSIWYG.
    Given I am an anonymous user
    When I visit "civictheme_page" "[TEST] CivicTheme Video and Remote Video"
    Then I should see the text "View transcript"
    And I see the ".civictheme-video-player iframe" element with the "title" attribute set to "[TEST] CivicTheme Remote Video"
    And I click on ".civictheme-video-player__links__transcript .civictheme-button--link" element
    And I should see the text "[TEST] CivicTheme Remote Video"
    And I should not see the text "[TEST] CivicTheme Remote Video Transcript"
