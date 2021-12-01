@civic @media @image_styles
Feature: Tests the civic image styles

  Ensure that civic image styles exist and have the expected fields.

  @api
  Scenario Outline: Image styles exist with fields.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/config/media/image-styles/manage/<machine_name>"
    And the "Image style name" field should contain "<style>"
    And I should see "<effect>"

    Examples:
      | style           | machine_name          | effect                 |
      | Medium          | civic_medium          | Scale 220×220          |
      | Thumbnail       | civic_thumbnail       | Scale 100×100          |
      | Navigation card | civic_navigation_card | Scale and crop 200×200 |
      | Promo card      | civic_promo_card      | Scale and crop 376×240 |
      | Promo banner    | civic_promo_banner    | Scale and crop 476×520 |
      | Navigation card | civic_navigation_card | Scale and crop 200×200 |
      | Topic mobile    | civic_topic_mobile    | Scale and crop 213×200 |
      | Topic desktop   | civic_topic_desktop   | Scale and crop 276×224 |
