@civictheme @civictheme_links
Feature: Check that content links have the correct classes.

  @api
  Scenario: Links in content have correct classes assigned.
    Given "civictheme_page" content:
      | title         | status |
      | [TEST] Page 1 | 1      |
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

    And I am logged in as a user with the "Site Administrator" role
    And I visit "/admin/appearance/settings/civictheme_demo"
    And I check the box "Open links in a new window"
    And I fill in "Override external link domains" with "http://exampleoverridden.com"
    And I press "Save configuration"
    And the "Open links in a new window" checkbox should be checked

    When I visit "civictheme_page" "[TEST] Page 1"

    # Light.
    Then I should see an ".civictheme-basic-content a[href='/internal-relative-light-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='/internal-relative-light-link'].civictheme-theme-light" element
    And I should see an ".civictheme-basic-content a[href='/internal-relative-light-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='/internal-relative-light-link'].civictheme-link.civictheme-link--external" element

    And I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-light-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-light-link'].civictheme-theme-light" element
    And I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-light-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-light-link'].civictheme-link.civictheme-link--external" element

    And I should see an ".civictheme-basic-content a[href='http://example.com/external-light-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-light-link'].civictheme-theme-light" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-light-link'][target='_blank'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-light-link'].civictheme-link.civictheme-link--external" element

    And I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-light-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-light-link'].civictheme-theme-light" element
    And I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-light-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-light-link'].civictheme-link.civictheme-link--external" element

    # Dark.
    Then I should see an ".civictheme-basic-content a[href='/internal-relative-dark-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='/internal-relative-dark-link'].civictheme-theme-dark" element
    And I should see an ".civictheme-basic-content a[href='/internal-relative-dark-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='/internal-relative-dark-link'].civictheme-link.civictheme-link--external" element

    And I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-dark-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-dark-link'].civictheme-theme-dark" element
    And I should see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-dark-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='http://nginx:8080/internal-absolute-dark-link'].civictheme-link.civictheme-link--external" element

    And I should see an ".civictheme-basic-content a[href='http://example.com/external-dark-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-dark-link'].civictheme-theme-dark" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-dark-link'][target='_blank'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://example.com/external-dark-link'].civictheme-link.civictheme-link--external" element

    And I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-dark-link'].civictheme-link" element
    And I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-dark-link'].civictheme-theme-dark" element
    And I should see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-dark-link'][target='_blank'].civictheme-link" element
    And I should not see an ".civictheme-basic-content a[href='http://exampleoverridden.com/external-dark-link'].civictheme-link.civictheme-link--external" element
