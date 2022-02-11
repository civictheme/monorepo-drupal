@civic @media @civic_remote_video
Feature: Tests the media types civic remote video

  Ensure that civic remote video are rendered correctly.

  Background:
    Given "civic_remote_video" media:
      | name                      | field_c_m_title           | field_c_m_oembed_video                      | field_c_m_transcript                 | uuid                                 |
      | [TEST] Civic Remote Video | [TEST] Civic Remote Video | https://www.youtube.com/watch?v=C0DPdy98e4c | [TEST] Civic Remote Video Transcript | 5bca9d25-2f72-4c41-9e89-a5d48dc1cd23 |
    And "civic_page" content:
      | title                               | status |
      | [TEST] Civic Video and Remote Video | 1      |
    And "field_c_n_components" in "civic_page" "node" with "title" of "[TEST] Civic Video and Remote Video" has "civic_content" paragraph:
      | field_c_p_content:value  | <h2>Test Video</h2> <h2>Test Remote Video</h2><br /><drupal-media data-align="center" data-entity-type="media" data-entity-uuid="5bca9d25-2f72-4c41-9e89-a5d48dc1cd23"></drupal-media> |
      | field_c_p_content:format | civic_rich_text                                                                                                                                                                        |

  @api @javascript @civic_remote_video
  Scenario: Ensure that civic remote video renders correctly and link correctly to transcript.
    Given I am an anonymous user
    When I visit "civic_page" "[TEST] Civic Video and Remote Video"
    And I should see the text "View transcript"
    And I see the ".civic-video iframe" element with the "title" attribute set to "[TEST] Civic Remote Video"
    And I click on ".civic-video__transcript .civic-link" element
    And I should see the text "[TEST] Civic Remote Video"
    And I should not see the text "[TEST] Civic Remote Video Transcript"
