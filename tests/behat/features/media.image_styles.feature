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
      | Campaign         | civictheme_campaign         | Focal Point Scale and Crop 600×600 |
      | Event Card       | civictheme_event_card       | Focal Point Scale and Crop 800×480 |
      | Navigation card  | civictheme_navigation_card  | Focal Point Scale and crop 600×600 |
      | Promo card       | civictheme_promo_card       | Focal Point Scale and crop 800×480 |
      | Publication Card | civictheme_publication_card | Focal Point Scale and Crop 600×600 |
      | Slider Slide     | civictheme_slider_slide     | Focal Point Scale and Crop 600×600 |
      | Subject Card     | civictheme_subject_card     | Focal Point Scale and Crop 600×600 |
