@p1 @civictheme @vocabulary @civictheme_media_tags
Feature: Civictheme media tags vocabulary roles access

  Ensure that civictheme_media_tags vocabulary permissions are set correctly
  for designated roles.

  Background: Default vocabulary provided
    Given civictheme_media_tags terms:
      | name             | parent | tid      |
      | Test Media Tag 1 | 0      | 60000001 |

  @api @nosuggest
  Scenario Outline: Media type permisson validated by designated users
    Given I am logged in as a user with the "<role>" role

    When I go to "/admin/structure/taxonomy/manage/civictheme_media_tags/add"
    Then I should get a "<response_add>" HTTP response
    When I go to "/taxonomy/term/60000001/edit"
    Then I should get a "<response_edit>" HTTP response
    When I go to "/taxonomy/term/60000001/delete"
    Then I should get a "<response_delete>" HTTP response

    Examples:
      | role                          | response_add | response_edit | response_delete |
      | civictheme_site_administrator | 200          | 200           | 200             |
      | civictheme_content_author     | 200          | 200           | 200             |
      | civictheme_content_approver   | 403          | 403           | 403             |
