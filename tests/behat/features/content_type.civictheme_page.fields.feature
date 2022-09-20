@civictheme @content_type @civictheme_page
Feature: Fields on CivicTheme Event content type

  Ensure that fields have been setup correctly.

  @api
  Scenario: CivicTheme Page content type exists with fields
    Given I am logged in as a user with the "Administrator" role
    When I go to "admin/structure/types/manage/civictheme_page/fields"
    Then I should see the text "Banner background" in the "field_c_n_banner_background" row
    And I should see the text "field_c_n_banner_components" in the "Banner bottom components" row
    And I should see the text "field_c_n_banner_components" in the "Banner components" row
    And I should see the text "Banner featured image" in the "field_c_n_banner_featured_image" row
    And I should see the text "Banner title" in the "field_c_n_banner_title" row
    And I should see the text "Banner type" in the "field_c_n_banner_type" row
    And I should see the text "Components" in the "field_c_n_components" row
    And I should see the text "Hide breadcrumb" in the "field_c_n_banner_hide_breadcrumb" row
    And I should see the text "Show Table of Contents" in the "field_c_n_show_toc" row
    And I should see the text "Site section" in the "field_c_n_site_section" row
    And I should see the text "Summary" in the "field_c_n_summary" row
    And I should see the text "Thumbnail" in the "field_c_n_thumbnail" row
    And I should see the text "Topics" in the "field_c_n_topics" row
    And I should see the text "Vertical spacing" in the "field_c_n_vertical_spacing" row

  @api
  Scenario: CivicTheme Page content type form has the relevant fields
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_page"
    Then the response status code should be 200

    And I see field "Title"
    And should see an "input#edit-title-0-value" element
    And should see an "input#edit-title-0-value.required" element
    And should not see an "input#edit-title-0-value[disabled]" element

    And I see field "Summary"
    And should see a "textarea#edit-field-c-n-summary-0-value" element
    And should not see a "textarea#edit-field-c-n-summary-0-value.required" element
    And should not see a "textarea#edit-field-c-n-summary-0-value[disabled]" element

    And I see field "Site section"
    And should see a "select#edit-field-c-n-site-section" element
    And should not see a "select#edit-field-c-n-site-section.required" element
    And should not see a "select#edit-field-c-n-site-section[disabled]" element

    And I see field "Topics"
    And should see a "input#edit-field-c-n-topics-0-target-id" element
    And should not see a "input#edit-field-c-n-topics-0-target-id.required" element
    And should not see a "input#edit-field-c-n-topics-0-target-id[disabled]" element

    And I should see the text "Thumbnail"
    And should see a "input#edit-field-c-n-thumbnail-open-button" element
    And should not see a "input#edit-field-c-n-thumbnail-open-button.required" element
    And should not see a "input#edit-field-c-n-thumbnail-open-button[disabled]" element

    And I see field "Vertical spacing"
    And should see an "select#edit-field-c-n-vertical-spacing" element
    And should not see a "select#edit-field-c-n-vertical-spacing.required" element
    And should not see a "select#edit-field-c-n-vertical-spacing[disabled]" element

    And I see field "Hide sidebar"
    And should see an "input#edit-field-c-n-hide-sidebar-value" element
    And should not see a "input#edit-field-c-n-hide-sidebar-value.required" element
    And should not see a "input#edit-field-c-n-hide-sidebar-value[disabled]" element

    And I should see the text "Banner type"
    And should see an "fieldset#edit-field-c-n-banner-type--wrapper" element
    And should see a "fieldset#edit-field-c-n-banner-type--wrapper.required" element
    And should not see a "fieldset#edit-field-c-n-banner-type--wrapper[disabled]" element

    And I should see the text "Banner theme"
    And should see a "fieldset#edit-field-c-n-banner-theme--wrapper" element
    And should see a "fieldset#edit-field-c-n-banner-theme--wrapper.required" element
    And should not see a "fieldset#edit-field-c-n-banner-theme--wrapper[disabled]" element

    And I see field "Hide breadcrumb"
    And should see a "input#edit-field-c-n-banner-hide-breadcrumb-value" element
    And should not see a "input#edit-field-c-n-banner-hide-breadcrumb-value.required" element
    And should not see a "input#edit-field-c-n-banner-hide-breadcrumb-value[disabled]" element

    And I see field "Banner title"
    And should see an "input#edit-field-c-n-banner-title-0-value" element
    And should not see a "input#edit-field-c-n-banner-title-0-value.required" element
    And should not see a "input#edit-field-c-n-banner-title-0-value[disabled]" element

    And I should see the text "Banner background"
    And should see a "input#edit-field-c-n-banner-background-open-button" element
    And should not see a "input#edit-field-c-n-banner-background-open-button.required" element
    And should not see a "input#edit-field-c-n-banner-background-open-button[disabled]" element

    And I should see the text "Banner featured image"
    And should see a "input#edit-field-c-n-banner-featured-image-open-button" element
    And should not see a "input#edit-field-c-n-banner-featured-image-open-button.required" element
    And should not see a "input#edit-field-c-n-banner-featured-image-open-button[disabled]" element

    And I should see text matching "Banner components"
    And should see an "input[name='field_c_n_banner_components_civictheme_content_add_more']" element

    And I should see text matching "Banner bottom components"
    And should see an "input[name='field_c_n_banner_components_bott_civictheme_content_add_more']" element

    And I see field "Show Table of Contents"
    And I should see a "input#edit-field-c-n-show-toc-value" element
    And I should not see a "input#edit-field-c-n-show-toc-value.required" element
    And I should not see a "input#edit-field-c-n-show-toc-value[disabled]" element

    And I should see text matching "Components"
    And should see an "input[name='field_c_n_components_civictheme_content_add_more']" element

    And I see field "Show Last updated date"
    And I should see a "input#edit-field-c-n-show-last-updated-value" element
    And I should not see a "input#edit-field-c-n-show-last-updated-value.required" element
    And I should not see a "input#edit-field-c-n-show-last-updated-value[disabled]" element

    And I should see the text "Custom Last updated date"
    And I should see a "input#edit-field-c-n-custom-last-updated-0-value-date" element
    And I should not see a "input#edit-field-c-n-custom-last-updated-0-value-date.required" element
    And I should not see a "input#edit-field-c-n-custom-last-updated-0-value-date[disabled]" element

    And I see field "Published"
    And I should see a "input#edit-status-value" element
    And I should not see a "input#edit-status-value.required" element
    And I should not see a "input#edit-status-value[disabled]" element
