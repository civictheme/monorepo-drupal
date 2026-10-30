@p1 @civictheme @civictheme_links
Feature: Content links processing

  Background:

  @api
  Scenario: Links in content have correct classes assigned.
    Given "civictheme_page" content:
      | title         | status | moderation_state |
      | [TEST] Page 1 | 1      | published        |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="/internal-relative-light-link">Internal relative light link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                     |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://nginx:8080/internal-absolute-light-link">Internal absolute light link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                                      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://example.com/external-light-link">External light link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                     |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://exampleoverridden.com/external-light-link">External light link from overridden domain</a> |
      | field_c_p_content:format | civictheme_rich_text                                                                                      |

    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="/internal-relative-dark-link">Internal relative dark link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                   |
      | field_c_p_theme          | dark                                                                   |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://nginx:8080/internal-absolute-dark-link">Internal absolute dark link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                                    |
      | field_c_p_theme          | dark                                                                                    |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://example.com/external-dark-link">External dark link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                   |
      | field_c_p_theme          | dark                                                                   |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://exampleoverridden.com/external-dark-link">External dark link from overridden domain</a> |
      | field_c_p_content:format | civictheme_rich_text                                                                                    |
      | field_c_p_theme          | dark                                                                                                    |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="///C:/Users/civictheme/invalid">Invalid link</a> |
      | field_c_p_content:format | civictheme_rich_text                                      |
      | field_c_p_theme          | dark                                                      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="tel:123412341234">Telephone link</a> |
      | field_c_p_content:format | civictheme_rich_text                          |
      | field_c_p_theme          | dark                                          |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="no-slash">Telephone link</a> |
      | field_c_p_content:format | civictheme_rich_text                  |
      | field_c_p_theme          | dark                                  |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="mailto:test@example.com">Telephone link</a> |
      | field_c_p_content:format | civictheme_rich_text                                 |
      | field_c_p_theme          | dark                                                 |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | person@test.com      |
      | field_c_p_content:format | civictheme_rich_text |
      | field_c_p_theme          | dark                 |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | http://www.test-link.com |
      | field_c_p_content:format | civictheme_rich_text     |
      | field_c_p_theme          | dark                     |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | person@test.com      |
      | field_c_p_content:format | civictheme_rich_text |

    And I am logged in as a user with the "Site Administrator" role
    And I visit current theme settings page
    And I check the box "Open links in a new window"
    And I fill in "Override external link domains" with "http://exampleoverridden.com"
    And I press "Save configuration"
    And the "Open links in a new window" checkbox should be checked

    When I visit "civictheme_page" "[TEST] Page 1"

    # Links are styled by the content component, so no link classes are added.
    Then I should not see an ".ct-basic-content a.ct-content-link" element

    # Light.
    # All links open in a new window, with the last word grouped with the
    # screen reader text and the external icon so that the icon does not wrap
    # onto a line of its own.
    And I should see an ".ct-basic-content.ct-theme-light a[href='/internal-relative-light-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-light a[href='/internal-relative-light-link'] .ct-text-no-wrap .ct-visually-hidden" element
    And I should see an ".ct-basic-content.ct-theme-light a[href='/internal-relative-light-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-light a[href='http://nginx:8080/internal-absolute-light-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-light a[href='http://nginx:8080/internal-absolute-light-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-light a[href='http://example.com/external-light-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-light a[href='http://example.com/external-light-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-light a[href='http://exampleoverridden.com/external-light-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-light a[href='http://exampleoverridden.com/external-light-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-light a[href='mailto:person@test.com']" element

    # Dark.
    And I should see an ".ct-basic-content.ct-theme-dark a[href='/internal-relative-dark-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='/internal-relative-dark-link'] .ct-text-no-wrap .ct-visually-hidden" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='/internal-relative-dark-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://nginx:8080/internal-absolute-dark-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://nginx:8080/internal-absolute-dark-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://example.com/external-dark-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://example.com/external-dark-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://exampleoverridden.com/external-dark-link'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://exampleoverridden.com/external-dark-link'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    And I should see an ".ct-basic-content.ct-theme-dark a[href='///C:/Users/civictheme/invalid'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='///C:/Users/civictheme/invalid'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    # Emails are converted to anchors in default state.
    And I should see an ".ct-basic-content.ct-theme-dark a[href='mailto:person@test.com']" element
    # URLS are converted to anchors in default state.
    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://www.test-link.com'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://www.test-link.com'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element

    # Test with filter_url disabled - URLs and emails should not be converted to links.
    When I disable "filter_url" filter on "civictheme_rich_text"
    And the cache has been cleared
    And I visit "civictheme_page" "[TEST] Page 1"
    Then I should not see an ".ct-basic-content a[href='http://www.test-link.com']" element
    And I should not see an ".ct-basic-content a[href='mailto:person@test.com']" element

    # Re-enable filter_url and add components.link optout - URLs and emails should be converted to links.
    # Link conversion handled in text format when rendered.
    When I enable "filter_url" filter on "civictheme_rich_text"
    And the cache has been cleared
    And I add "components.link" optout to theme settings
    And the cache has been cleared
    And I visit "civictheme_page" "[TEST] Page 1"
    Then I should see an ".ct-basic-content a[href='http://www.test-link.com']" element
    And I should see an ".ct-basic-content a[href='mailto:person@test.com']" element
    # Remove components.link optout - URLs and emails should be converted to links and processed.
    When I remove "components.link" optout from theme settings
    And the cache has been cleared
    And I visit "civictheme_page" "[TEST] Page 1"
    Then I should see an ".ct-basic-content.ct-theme-dark a[href='http://www.test-link.com'][target='_blank']" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='http://www.test-link.com'] .ct-text-no-wrap svg.ct-basic-content__external-icon" element
    And I should see an ".ct-basic-content.ct-theme-dark a[href='mailto:person@test.com']" element


