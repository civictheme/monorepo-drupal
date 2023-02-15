@p0 @civictheme @content_type @civictheme_event
Feature: CivicTheme Event content type fields

  @api
  Scenario: Fields appear as expected
    Given I am logged in as a user with the "Site Administrator" role
    When I visit "node/add/civictheme_event"
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

    And I see field "Body"
    And should see an "[name='field_c_n_body[0][value]']" element
    And should see an "[name='field_c_n_body[0][value]'].required" element
    And should not see an "[name='field_c_n_body[0][value]'][disabled]" element

    And I should see the text "Thumbnail"
    And should see an "[name='field_c_n_thumbnail[media_library_selection]']" element

    And I see field "Vertical spacing"
    And should see an "[name='field_c_n_vertical_spacing']" element
    And should see an "[name='field_c_n_vertical_spacing'].required" element
    And should not see an "[name='field_c_n_vertical_spacing'][disabled]" element

    And I should see the text "Start date"
    And should see an "[name='field_c_n_date_range[0][value][date]']" element
    And should not see an "[name='field_c_n_date_range[0][value][date]'].required" element
    And should not see an "[name='field_c_n_date_range[0][value][date]'][disabled]" element
    And I should see the text "End date"
    And should see an "[name='field_c_n_date_range[0][end_value][date]']" element
    And should not see an "[name='field_c_n_date_range[0][end_value][date]'].required" element
    And should not see an "[name='field_c_n_date_range[0][end_value][date]'][disabled]" element

    And I see field "Address"

    And I should see a "[name='field_c_n_location[0][subform][field_c_p_address][0][value]']" element
    And I should see a "[name='field_c_n_location[0][subform][field_c_p_address][0][value]'].required" element
    And I should not see a "[name='field_c_n_location[0][subform][field_c_p_address][0][value]'][disabled]" element

    And I should see a "[name='field_c_n_location[0][subform][field_c_p_view_link][0][uri]']" element
    And I should not see a "[name='field_c_n_location[0][subform][field_c_p_view_link][0][uri]'].required" element
    And I should not see a "[name='field_c_n_location[0][subform][field_c_p_view_link][0][uri]'][disabled]" element

    And I should see a "[name='field_c_n_location[0][subform][field_c_p_embed_url][0][uri]']" element
    And I should see a "[name='field_c_n_location[0][subform][field_c_p_embed_url][0][uri]'].required" element
    And I should not see a "[name='field_c_n_location[0][subform][field_c_p_embed_url][0][uri]'][disabled]" element

    And I should see a "[name='field_c_n_location[0][subform][field_c_p_theme]']" element
    And I should see a "[name='field_c_n_location[0][subform][field_c_p_background][value]']" element

    And I should see a "[name='field_c_n_location[0][subform][field_c_p_vertical_spacing]']" element
    And I should see a "[name='field_c_n_location[0][subform][field_c_p_vertical_spacing]'].required" element

    And I see field "Show Last updated date"
    And I should see a "[name='field_c_n_show_last_updated[value]']" element
    And I should not see a "[name='field_c_n_show_last_updated[value]'].required" element
    And I should not see a "[name='field_c_n_show_last_updated[value]'][disabled]" element

    And I should see the text "Custom Last updated date"
    And I should see a "[name='field_c_n_custom_last_updated[0][value][date]']" element
    And I should not see a "[name='field_c_n_custom_last_updated[0][value][date]'].required" element
    And I should not see a "[name='field_c_n_custom_last_updated[0][value][date]'][disabled]" element
