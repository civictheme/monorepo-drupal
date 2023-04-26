This document is used as a starting point to define a "contract" between field
names provided in MerlinUI when defining extractors so that consumer migration
modules could define such field as "source" fields.

## Legend

The table below describes the meaning of the columns in the tables describing
migration mappings.

Such tables created for each entity type and bundle.

They have identical structure and meaning of the columns.

| Table column          | Purpose                                                                                                                                                                                 |
|-----------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| Field description     | The purpose of the field                                                                                                                                                                |
| Required              | Whether the field value is required to be provided in the migration source files                                                                                                        |
| Merlin UI             | The field name in the migration **source** coming from Merlin UI                                                                                                                        |
| Merlin UI version     | The version of Merlin UI when the field was introduced with the name specified in "Merlin UI field name" column.                                                                        |
| CivicTheme field name | The field name in the migration **destination** coming from Merlin UI                                                                                                                   |
| CivicTheme version    | The version of CivicTheme when the field was introduced with the name specified in "CivicTheme field name" column.                                                                      |
| Status                | - `draft` - field mapping is proposed for a review<br/>- `in review` - field mapping is in review by all parties<br/>- `approved` - field mapping is approved and ready to be used<br/> |

## Content

### Page (`civictheme_page`)

| Field description                       | Required | Merlin UI field name             | Merlin UI version | CivicTheme field name                        | CivicTheme version | Status   |
|-----------------------------------------|----------|----------------------------------|-------------------|----------------------------------------------|--------------------|----------|
| Page ID (Migration ID)                  | Yes      | `id`                             | 1.5               | `id`                                         | 1.4                | approved |
| Page title                              | Yes      | `title`                          | 1.5               | `title`                                      | 1.4                | approved |
| Page URL alias                          | No       | `alias`                          | 1.5               | `alias`                                      | 1.4                | approved |
| Page Summary                            | No       | `summary`                        | 1.5               | `field_n_summary`                            | 1.4                | approved |
| Topics (comma separated list of topics) | No       | `topics`                         | 1.5               | `field_c_n_topics`                           | 1.4                | approved |
| Thumbnail                               | No       | `thumbnail`                      | 1.5               | `field_c_n_thumbnail`                        | 1.4                | approved |
| Vertical spacing                        | No       | `vertical_spacing`               | 1.5               | `field_c_n_vertical_spacing`                 | 1.4                | approved |
| Hide sidebar                            | No       | `hide_sidebar`                   | 1.5               | `field_c_n_hide_sidebar`                     | 1.4                | approved |
| Show last updated date                  | No       | `show_last_updated_date`         | 1.5               | `field_c_n_show_last_updated`                | 1.4                | approved |
| Last updated date                       | No       | `last_updated_date`              | 1.5               | `field_c_n_custom_last_updated`              | 1.4                | approved |
| Show table of contents                  | No       | `show_toc`                       | 1.5               | `field_c_n_show_toc`                         | 1.4                | approved |
| Container for banner items              | No       | `banner`                         | 1.5               |                                              | 1.4                | approved |
| Banner theme                            | No       | `banner.theme`                   | 1.5               | `field_c_n_banner_theme`                     | 1.4                | approved |
| Banner title                            | No       | `banner.title`                   | 1.5               | `field_c_n_banner_title`                     | 1.4                | approved |
| Banner type                             | No       | `banner.type`                    | 1.5               | `field_c_n_banner_type`                      | 1.4                | approved |
| Banner blend mode                       | No       | `banner.blend_mode`              | 1.5               | `field_c_n_blend_mode`                       | 1.4                | approved |
| Banner featured image                   | No       | `banner.featured_image`          | 1.5               | `field_c_n_banner_featured_image`            | 1.4                | approved |
| Banner background image                 | No       | `banner.background`              | 1.5               | `field_c_n_banner_background_image`          | 1.4                | approved |
| Hide breadcrumb                         | No       | `banner.hide_breadcrumb`         | 1.5               | `field_c_n_banner_hide_breadcrumb`           | 1.4                | approved |
| Container for components                | No       | `content`                        | 1.5               | `field_c_n_components`                       | 1.4                | approved |
| Container for Basic text component      | No       | `content.text_content`           | 1.5               |                                              | 1.4                | approved |
| Container for Manual list component     | No       | `content.manual_list`            | 1.5               |                                              | 1.4                | approved |
| Container for Accordion component       | No       | `content.accordion`              | 1.5               |                                              | 1.4                | approved |
| Container for Attachment component      | No       | `content.attachment`             | 1.5               |                                              | 1.4                | approved |
| Attachment component title              | No       | `content.attachment.title`       | 1.5               | `field_c_n_components.field_c_p_title`       | 1.4                | approved |
| Attachment component content            | No       | `content.attachment.content`     | 1.5               | `field_c_n_components.field_c_p_content`     | 1.4                | approved |
| Attachment component attachments        | No       | `content.attachment.attachments` | 1.5               | `field_c_n_components.field_c_p_attachments` | 1.4                | approved |
|                                         |

### Event (`civictheme_event`)

TBD

## Media

### Image (`civictheme_image`)

| Field description | Required | Merlin UI field name | Merlin UI version | CivicTheme field name | CivicTheme version | Status   |
|-------------------|----------|----------------------|-------------------|-----------------------|--------------------|----------|
| Unique identifier | Yes      | `uuid`               | 1.5               | `id`                  | 1.4                | approved |
| Image name        | Yes      | `name`               | 1.5               | `name`                | 1.4                | approved |
| File URL          | Yes      | `file`               | 1.5               | `field_c_m_image`     | 1.4                | approved |
| Alternative text  | No       | `alt`                | 1.5               | `field_c_m_image`     | 1.4                | approved |


### Document (`civictheme_document`)

| Field description | Required | Merlin UI field name | Merlin UI version | CivicTheme field name | CivicTheme version | Status   |
|-------------------|----------|----------------------|-------------------|-----------------------|--------------------|----------|
| Unique identifier | Yes      | `uuid`               | 1.5               | `id`                  | 1.4                | approved |
| Document name     | Yes      | `name`               | 1.5               | `name`                | 1.4                | approved |
| File URL          | Yes      | `file`               | 1.5               | `field_c_m_document`  | 1.4                | approved |


### Video (`civictheme_video`)

TBD

### Remote video (`civictheme_remote_video`)

TBD

### Audio (`civictheme_audio`)

TBD


## Menus

### Primary (`civictheme-primary-navigation`)

| Field description     | Required | Merlin UI field name | Merlin UI version | CivicTheme field name | CivicTheme version | Status   |
|-----------------------|----------|----------------------|-------------------|-----------------------|--------------------|----------|
| Unique identifier     | Yes      | `uuid`               | 1.5               | `id`                  | 1.4                | approved |
| Menu item text        | Yes      | `text`               | 1.5               | `title`               | 1.4                | approved |
| Menu item URL         | Yes      | `link`               | 1.5               | `link`                | 1.4                | approved |
| Parent menu item UUID | Yes      | `parent`             | 1.5               | `parent`              | 1.4                | approved |
| Weight                | Yes      | `weight`             | 1.5               | `weight`              | 1.4                | approved |


### Secondary (`civictheme-secondary-navigation`)

| Field description     | Required | Merlin UI field name | Merlin UI version | CivicTheme field name | CivicTheme version | Status   |
|-----------------------|----------|----------------------|-------------------|-----------------------|--------------------|----------|
| Unique identifier     | Yes      | `uuid`               | 1.5               | `id`                  | 1.4                | approved |
| Menu item text        | Yes      | `text`               | 1.5               | `title`               | 1.4                | approved |
| Menu item URL         | Yes      | `link`               | 1.5               | `link`                | 1.4                | approved |
| Parent menu item UUID | Yes      | `parent`             | 1.5               | `parent`              | 1.4                | approved |
| Weight                | Yes      | `weight`             | 1.5               | `weight`              | 1.4                | approved |


### Footer (`civictheme-footer`)

| Field description     | Required | Merlin UI field name | Merlin UI version | CivicTheme field name | CivicTheme version | Status   |
|-----------------------|----------|----------------------|-------------------|-----------------------|--------------------|----------|
| Unique identifier     | Yes      | `uuid`               | 1.5               | `id`                  | 1.4                | approved |
| Menu item text        | Yes      | `text`               | 1.5               | `title`               | 1.4                | approved |
| Menu item URL         | Yes      | `link`               | 1.5               | `link`                | 1.4                | approved |
| Parent menu item UUID | Yes      | `parent`             | 1.5               | `parent`              | 1.4                | approved |
| Weight                | Yes      | `weight`             | 1.5               | `weight`              | 1.4                | approved |

