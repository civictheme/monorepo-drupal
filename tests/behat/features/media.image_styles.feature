@civic @media @image_styles
Feature: Tests the civic image styles

  Ensure that civic image styles exist and have the expected fields.

  @api
  Scenario Outline: Image styles exist with fields.
    Given I am logged in as a user with the "Civic Site Administrator" role
    When I go to "admin/config/media/image-styles/manage/<machine_name>"
    And the "Image style name" field should contain "<style>"
    And I should see <effect>

    Examples:
      | style           | machine_name   | effect                   |
      | Promo card      | promo_card     | "Scale and crop 376×240" |
      | Navigation card | navigation     | "Scale and crop 200×200" |
      | Topic mobile    | topic__mobile  | "Scale and crop 213×200" |
      | Topic desktop   | topic__desktop | "Scale and crop 276×224" |
      | Promo banner    | promo_banner   | "Scale and crop 476×520" |
