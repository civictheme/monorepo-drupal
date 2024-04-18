@p0 @civictheme @media @image_styles
Feature: Image styles

  @api
  Scenario Outline: Image styles exist with settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/config/media/image-styles/manage/<machine_name>"
    And the "Image style name" field should contain "<style>"
    And I should see "<effect>"

    Examples:
      | style           | machine_name               | effect                             |
      | Medium          | civictheme_medium          | Focal Point Scale and Crop 220×220 |
      | Thumbnail       | civictheme_thumbnail       | Focal Point Scale and Crop 100×100 |
      | Navigation card | civictheme_navigation_card | Focal Point Scale and crop 200×200 |
      | Promo card      | civictheme_promo_card      | Focal Point Scale and crop 376×240 |
      | Promo banner    | civictheme_promo_banner    | Focal Point Scale and crop 476×520 |
      | Navigation card | civictheme_navigation_card | Focal Point Scale and crop 200×200 |
      | Topic mobile    | civictheme_topic_mobile    | Focal Point Scale and crop 213×200 |
      | Topic desktop   | civictheme_topic_desktop   | Focal Point Scale and crop 276×224 |
