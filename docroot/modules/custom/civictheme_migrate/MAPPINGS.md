This document is used as a starting point to define a "contract" between field
names provided in MerlinUI when defining extractors so that consumer migration
modules could define such field as "source" fields.

Status:

- `draft` - field mapping is proposed for a review
- `in review` - field mapping is in review by all parties
- `approved` - field mapping is approved and ready to be used

| Merlin UI                        | Description                             | Merlin type | Required | Status   | CivicTheme                                   | CivicTheme version |
| -------------------------------- | --------------------------------------- | ----------- | -------- | -------- | -------------------------------------------- | ------------------ |
| `id`                             | Page ID (Migration ID)                  | `uuid`      | Yes      | approved | `id`                                         | 1.4                |
| `title`                          | Page title                              | `text`      | Yes      | approved | `title`                                      | 1.4                |
| `alias`                          | Page URL alias                          | `alias`     | No       | approved | `alias`                                      | 1.4                |
| `summary`                        | Page Summary                            | `text`      | No       | approved | `field_n_summary`                            | 1.4                |
| `topics`                         | Topics (comma separated list of topics) | `text`      | No       | approved | `field_c_n_topics`                           | 1.4                |
| `thumbnail`                      | Thumbnail                               | `Media`     | No       | approved | `field_c_n_thumbnail`                        | 1.4                |
| `vertical_spacing`               | Vertical spacing                        | `text`      | No       | approved | `field_c_n_vertical_spacing`                 | 1.4                |
| `hide_sidebar`                   | Hide sidebar                            | `boolean`   | No       | approved | `field_c_n_hide_sidebar`                     | 1.4                |
| `show_last_updated_date`         | Show last updated date                  |             | No       | approved | `field_c_n_show_last_updated`                | 1.4                |
| `last_updated_date`              | Last updated date                       |             | No       | approved | `field_c_n_custom_last_updated`              | 1.4                |
| `show_toc`                       | Show table of contents                  |             | No       | approved | `field_c_n_show_toc`                         | 1.4                |
| `banner`                         | Container for banner items              | `container` | No       | approved |                                              | 1.4                |
| `banner.theme`                   | Banner theme                            |             | No       | approved | `field_c_n_banner_theme`                     | 1.4                |
| `banner.title`                   | Banner title                            | `text`      | No       | approved | `field_c_n_banner_title`                     | 1.4                |
| `banner.type`                    | Banner type                             | `text`      | No       | approved | `field_c_n_banner_type`                      | 1.4                |
| `banner.blend_mode`              | Banner blend mode                       |             | No       | approved | `field_c_n_blend_mode`                       | 1.4                |
| `banner.featured_image`          | Banner featured image                   | `Media`     | No       | approved | `field_c_n_banner_featured_image`            | 1.4                |
| `banner.background`              | Banner background image                 | `Media`     | No       | approved | `field_c_n_banner_background_image`          | 1.4                |
| `banner.hide_breadcrumb`         | Hide breadcrumb                         | `boolean`   | No       | approved | `field_c_n_banner_hide_breadcrumb`           | 1.4                |
| `content`                        | Container for components                | `container` | No       | draft    | `field_c_n_components`                       | 1.4                |
| `content.text_content`           | Container for Basic text component      | `container` | No       | draft    | `field_c_n_components`                       |                    |
| `content.manual_list`            | Container for Manual list component     | `container` | No       | draft    |                                              |                    |
| `content.accordion`              | Container for Accordion component       | `container` | No       | draft    |                                              |                    |
| `content.attachment`             | Container for Attachment component      | `container` | No       | draft    |                                              |                    |
| `content.attachment.title`       | Attachment component title              | `container` | No       | draft    | `field_c_n_components.field_c_p_title`       |                    |
| `content.attachment.content`     | Attachment component content            | `container` | No       | draft    | `field_c_n_components.field_c_p_content`     |                    |
| `content.attachment.attachments` | Attachment component attachments        | `container` | No       | draft    | `field_c_n_components.field_c_p_attachments` |                    |
|                                  |
