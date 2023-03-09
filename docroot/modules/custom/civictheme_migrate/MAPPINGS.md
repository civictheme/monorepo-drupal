This document is used as a starting point to define a “contract” between field names provided in MerlinUI when defining extractors so that consumer migration modules could define such field as “source” fields.

It is a WIP - will be filled-in progressively.

Status:
- DRAFT - field mapping is proposed for review
- IN REVIEW - field mapping is in review by all parties
- APPROVED - field mapping is approved and ready to be used


| Merlin UI1                        | Description                             | Merlin type | Other settings | Status   | CivicTheme | SDP Tide |
|-----------------------------------|-----------------------------------------|-------------|----------------|----------|------------|----------|
| id                                | Page ID (Migration ID)                  | uuid        | Required       | draft    |            |          |
| id                                | id                                      |             |                |          |            |          |
| title                             | Page title                              | text        | Required       | Approved |            |          |
| title                             | title                                   |             |                |          |            |          |
| alias                             | Page URL alias                          | alias       |                | draft    |            |          |
| path/alias                        | path/alias                              |             |                |          |            |          |
| summary                           | Page Summary                            | text        |                | draft    |            |          |
| field_n_summary                   |                                         |             |                |          |            |          |
| topics                            | Topics (comma separated list of topics) | text        |                | draft    |            |          |
| field_c_n_topics                  |                                         |             |                |          |            |          |
| thumbnail                         | Thumbnail                               | Media       |                | draft    |            |          |
| field_c_n_thumbnail               |                                         |             |                |          |            |          |
| vertical_spacing                  | Vertical spacing                        | text        |                | draft    |            |          |
| field_c_n_vertical_spacing        |                                         |             |                |          |            |          |
| hide_sidebar                      | Hide sidebar                            | boolean     |                | draft    |            |          |
| field_c_n_hide_sidebar            |                                         |             |                |          |            |          |
| show_last_updated_date            | Show last updated date                  |             |                | draft    |            |          |
| field_c_n_show_last_updated       |                                         |             |                |          |            |          |
| last_updated_date                 | Last updated date                       |             |                | draft    |            |          |
| field_c_n_custom_last_updated     |                                         |             |                |          |            |          |
| show_toc                          | Show table of contents                  |             |                | draft    |            |          |
| field_c_n_show_toc                |                                         |             |                |          |            |          |
| banner                            | Container for banner items              | container   |                | draft    |            |          |
|                                   |                                         |             |                |          |            |          |
| banner.theme                      | Banner theme                            |             |                | draft    |            |          |
| field_c_n_banner_theme            |                                         |             |                |          |            |          |
| banner.title                      | Banner title                            | text        |                | draft    |            |          |
| field_c_n_banner_title            |                                         |             |                |          |            |          |
| banner.banner_type                | Banner type                             | text        |                | draft    |            |          |
| field_c_n_banner_type             |                                         |             |                |          |            |          |
| banner.blend_mode                 | Banner blend mode                       |             |                | draft    |            |          |
| field_c_n_blend_mode              |                                         |             |                |          |            |          |
| banner.featured_image             | Banner featured image                   | Media       |                | draft    |            |          |
| field_c_n_banner_featured_image   |                                         |             |                |          |            |          |
| banner.background                 | Banner background image                 | Media       |                | draft    |            |          |
| field_c_n_banner_background_image |                                         |             |                |          |            |          |
| banner.hide_breadcrumb            | Hide breadcrumb                         | boolean     |                | draft    |            |          |
| field_c_n_hide_breadcrumb         |                                         |             |                |          |            |          |
