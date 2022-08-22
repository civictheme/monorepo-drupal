@civictheme @civictheme_links
Feature: Check that content links have the correct classes.

  @api
  Scenario: Links in content have correct classes assigned.
    Given "civictheme_page" content:
      | title         | status |
      | [TEST] Page 1 | 1      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="/internal-relative-link">Internal relative link</a> |
      | field_c_p_content:format | civictheme_rich_text                                         |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://nginx:8080/internal-absolute-link">Internal absolute link</a> |
      | field_c_p_content:format | civictheme_rich_text                                                          |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://example.com/external-link">External link</a> |
      | field_c_p_content:format | civictheme_rich_text                                         |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page 1" has "civictheme_content" paragraph:
      | field_c_p_content:value  | <a href="http://exampleoverridden.com/external-link">External link from overridden domain</a> |
      | field_c_p_content:format | civictheme_rich_text                                                                          |

    And I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    And I check the box "Open links in a new window"
    And I fill in "Override external link domains" with "http://exampleoverridden.com"
    And I press "Save configuration"
    And the "Open links in a new window" checkbox should be checked

    When I visit "civictheme_page" "[TEST] Page 1"

    Then I should see an ".civictheme-basic-content a[href='/internal-relative-link'].civictheme-link" element
    Then I should see an ".civictheme-basic-content a[href='/internal-relative-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='/internal-relative-link'].civictheme-link.civictheme-link--external" element

    Then I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-link'].civictheme-link" element
    Then I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-link'].civictheme-link.civictheme-link--external" element

    Then I should see an ".civictheme-basic-content a[href='http://example.com/external-link'].civictheme-link" element
    Then I should see an ".civictheme-basic-content a[href='http://example.com/external-link'][target='_blank'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-link'].civictheme-link.civictheme-link--external" element

    Then I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-link'].civictheme-link" element
    Then I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-link'].civictheme-link.civictheme-link--external" element
