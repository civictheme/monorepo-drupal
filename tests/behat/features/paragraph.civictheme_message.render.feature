@p1 @civictheme @civictheme_message
Feature: Message render

  Background:
    Given "civictheme_page" content:
      | title                    | status |
      | [TEST] Page message test | 1      |

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with message light
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page message test" has "civictheme_message" paragraph:
      | field_c_p_title          | [TEST] message title                                                                               |
      | field_c_p_theme          | light                                                                                              |
      | field_c_p_content:value  | Content text                                                                                       |
      | field_c_p_content:format | civictheme_rich_text                                                                              |
      | field_c_p_message_type   | information                                                                                        |
      | field_c_p_background     | 1                                                                                                  |

    When I visit "civictheme_page" "[TEST] Page message test"
    Then I should see an ".ct-message" element
    And I should see an ".ct-message.ct-theme-light" element
    And I should not see an ".ct-message.ct-theme-dark" element
    And I should see an ".ct-message__title" element
    And I should see an ".ct-message__content" element
    And I should see the text "[TEST] message title"
    And I should see the text "Content text"

  @api
  Scenario: CivicTheme page content type page can be viewed by anonymous with message dark
    Given I am an anonymous user
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] Page message test" has "civictheme_message" paragraph:
      | field_c_p_title        | [TEST] message title |
      | field_c_p_theme        | dark                 |
      | field_c_p_content      | Content text         |
      | field_c_p_message_type | warning             |
      | field_c_p_background   | 0                    |

    When I visit "civictheme_page" "[TEST] Page message test"
    Then I should see an ".ct-message" element
    And I should see an ".ct-message.ct-theme-dark" element
    And I should not see an ".ct-message.ct-theme-light" element
    And I should see an ".ct-message__title" element
    And I should see an ".ct-message__content" element
    And I should see the text "[TEST] message title"
    And I should see the text "Content text"
