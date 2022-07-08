@civictheme @media @civictheme_image
Feature: Tests the media civictheme image

  Ensure that image is rendered correctly when embedded into WYSIWYG.

  @api @javascript
  Scenario: Ensure that a Image renders correctly when added to WYSIWYG.
    Given I am an anonymous user
    And managed file:
      | filename       | uri                                     | path           |
      | test_image.jpg | public://civictheme_test/test_image.jpg | test_image.jpg |
    And "civictheme_image" media:
      | name                    | field_c_m_image | uuid                                 |
      | [TEST] CivicTheme Image | test_image.jpg  | 5dfa9d25-2f42-7r41-9e89-a4548dc1df26 |
    And "civictheme_page" content:
      | title                  | status |
      | [TEST] CivicTheme Page | 1      |
    And "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] CivicTheme Page" has "civictheme_accordion" paragraph:
      | field_c_p_theme          | light |
      | field_c_p_background     | 1     |
      | field_c_p_expand         | 1     |
    And "field_c_p_panels" in "civictheme_accordion" "paragraph" parent "field_c_n_components" in "civictheme_page" "node" with "title" of "[TEST] CivicTheme Page" delta "0" has "civictheme_accordion_panel" paragraph:
      | field_c_p_title          | [TEST] Accordion panel 1              |
      | field_c_p_expand         | 1                                     |
      | field_c_p_content:value  | <h2>Test Image</h2> <h2>Test Image</h2><br /><drupal-media data-align="center" data-entity-type="media" data-entity-uuid="5dfa9d25-2f42-7r41-9e89-a4548dc1df26"></drupal-media> |
      | field_c_p_content:format | civictheme_rich_text                  |
    When I visit "civictheme_page" "[TEST] CivicTheme Page"
    And I should see the text "[TEST] CivicTheme Page"
    And should see an "div.civictheme-accordion__content .civictheme-figure img" element
