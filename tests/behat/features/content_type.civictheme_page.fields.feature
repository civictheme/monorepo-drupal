@p0 @civictheme @content_type @civictheme_page
Feature: CivicTheme Page content type fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "[name='title[0][value]']" element
    And should see an "[name='title[0][value]'].required" element
    And should not see an "[name='title[0][value]'][disabled]" element

    And I see field "Summary"
    And should see an "[name='field_c_n_summary[0][value]']" element
    And should not see an "[name='field_c_n_summary[0][value]'].required" element
    And should not see an "[name='field_c_n_summary[0][value]'][disabled]" element

    And I see field "Topics"
    And should see an "[name='field_c_n_topics[0][target_id]']" element
    And should not see an "[name='field_c_n_topics[0][target_id]'].required" element
    And should not see an "[name='field_c_n_topics[0][target_id]'][disabled]" element

    And I see field "Site section"
    And should see an "[name='field_c_n_site_section']" element
    And should not see an "[name='field_c_n_site_section'].required" element
    And should not see an "[name='field_c_n_site_section'][disabled]" element

    And I see field "Show Table of Contents"
    And should see an "[name='field_c_n_show_toc[value]']" element
    And should not see an "[name='field_c_n_show_toc[value]'].required" element
    And should not see an "[name='field_c_n_show_toc[value]'][disabled]" element

    And I should see text matching "Components"
    And should see an "[name='field_c_n_components_civictheme_content_add_more']" element

    And I should see the text "Thumbnail"
    And should see an "[name='field_c_n_thumbnail[media_library_selection]']" element

    And I see field "Vertical spacing"
    And should see an "[name='field_c_n_vertical_spacing']" element
    And should see an "[name='field_c_n_vertical_spacing'].required" element
    And should not see an "[name='field_c_n_vertical_spacing'][disabled]" element

    And I should see the text "Hide sidebar"
    And should see an "[name='field_c_n_hide_sidebar[value]']" element

    And I should see the text "Hide tags"
    And should see an "[name='field_c_n_hide_tags[value]']" element

    And I should see the text "Banner type"
    And should see an "[name='field_c_n_banner_type']" element

    And I should see the text "Banner theme"
    And should see an "[name='field_c_n_banner_theme']" element

    And I see field "Banner title"
    And should see an "[name='field_c_n_banner_title[0][value]']" element
    And should not see an "[name='field_c_n_banner_title[0][value]'].required" element
    And should not see an "[name='field_c_n_banner_title[0][value]'][disabled]" element

    And I see field "Hide breadcrumb"
    And should see an "[name='field_c_n_banner_hide_breadcrumb[value]']" element

    And I should see the text "Banner background"
    And should see an "[name='field_c_n_banner_background[media_library_selection]']" element

    And I see field "Banner background blend mode"
    And should see an "[name='field_c_n_blend_mode']" element
    And should see an "[name='field_c_n_blend_mode'].required" element
    And should not see an "[name='field_c_n_blend_mode'][disabled]" element

    And I should see the text "Banner featured image"
    And should see an "[name='field_c_n_banner_featured_image[media_library_selection]']" element

    And I should see text matching "Banner components"
    And should see an "input[name='field_c_n_banner_components_civictheme_content_add_more']" element

    And I should see text matching "Banner bottom components"
    And should see an "input[name='field_c_n_banner_components_bott_civictheme_content_add_more']" element

    And I see field "Show Last updated date"
    And I should see a "[name='field_c_n_show_last_updated[value]']" element
    And I should not see a "[name='field_c_n_show_last_updated[value]'].required" element
    And I should not see a "[name='field_c_n_show_last_updated[value]'][disabled]" element

    And I should see the text "Custom Last updated date"
    And I should see a "[name='field_c_n_custom_last_updated[0][value][date]']" element
    And I should not see a "[name='field_c_n_custom_last_updated[0][value][date]'].required" element
    And I should not see a "[name='field_c_n_custom_last_updated[0][value][date]'][disabled]" element
