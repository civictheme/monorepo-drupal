@p0 @civictheme @media @image_styles
Feature: Image styles

  @api
  Scenario Outline: Image styles exist with settings.
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/config/media/image-styles/manage/<machine_name>"
    And the "Image style name" field should contain "<style>"
    And I should see "<effect>"

    Examples:
      | style            | machine_name                | effect                             |
      | Navigation card  | civictheme_navigation_card  | Focal Point Scale and crop 200×200 |
      | Promo card       | civictheme_promo_card       | Focal Point Scale and crop 376×240 |
      | Promo banner     | civictheme_promo_banner     | Focal Point Scale and crop 476×520 |
      | Topic mobile     | civictheme_topic_mobile     | Focal Point Scale and crop 213×200 |
      | Topic desktop    | civictheme_topic_desktop    | Focal Point Scale and crop 276×224 |
      | Campaign         | civictheme_campaign         | Focal Point Scale and Crop 100×100 |
      | Event Card       | civictheme_event_card       | Focal Point Scale and Crop 100×100 |
      | Publication Card | civictheme_publication_card | Focal Point Scale and Crop 100×100 |
      | Slider Slide     | civictheme_slider_slide     | Focal Point Scale and Crop 100×100 |
      | Subject Card     | civictheme_subject_card     | Focal Point Scale and Crop 100×100 |
