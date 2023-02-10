# CivicTheme Migrate

Migration source files are imported via configuration form at
`/admin/configuration/civictheme-migrate`.

You can choose to manually upload the extracted content JSON files or retrieve
the extracted content JSON files from the remote API endpoint.

After configuring a migration you can then begin the migration and setup your
CivicTheme site.

## Extracted Content Validation

Extracted Content JSON files are validated using [JSON Schema](https://json-schema.org).
The schema used in validating the imported JSON files are located at
`schema/civictheme_migrate.extracted_content.schema.json`.

For an understanding of the structure of JSON Schema files [see JSON schema documentation](https://opis.io/json-schema/2.x/).

Validation is carried out using [opis/json-schema](https://github.com/opis/json-schema).

## Example valid JSON file

```json

[
  {
    "id": "4593761e-8a5d-4564-8c0e-2126fb4f3338",
    "title": "[TEST] Migrated Content 1",
    "alias": "/test/migrated-content-1",
    "summary": "Summary for [TEST] Migrated Content 1",
    "topics": "[TEST] Topic 1,[TEST] Topic 2,[TEST] Topic 3,[TEST] Topic 4",
    "thumbnail": [
      {
        "uuid": "f352fb5f-5319-4a09-a039-6b7080b31443",
        "name": "D10 launch.png",
        "file": "https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png",
        "alt": "Test alt text for thumbnail"
      }
    ],
    "vertical_spacing": "top",
    "hide_sidebar": true,
    "show_last_updated_date": true,
    "last_updated_date": "8 Oct 2022",
    "show_toc": true,
    "banner": {
      "type": "container",
      "children" : [
        {
          "theme": "dark",
          "title": "[TEST] Banner title - Migrated Content 1",
          "banner_type": "large",
          "blend_mode": "darken",
          "featured_image": [
            {
              "uuid": "f352fb5f-5319-4a09-a039-6b7080b31443",
              "name": "D10 launch.png",
              "file": "https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png",
              "alt": "Test alt text for thumbnail"
            }
          ],
          "background": [
            {
              "uuid": "427186ad-c561-4441-9951-28399d8a4923",
              "name": "demo_banner-background.png",
              "file": "https://www.civictheme.io/sites/default/files/demo_banner-background.png",
              "alt": ""
            }
          ],
          "hide_breadcrumb": true
        }
      ]
    }
  },
  {
    "id": "9ba51c5e-319c-47dd-8209-2029ed7525f1",
    "title": "[TEST] Migrated Content 2",
    "alias": "/test/migrated-content-2",
    "summary": "Summary for [TEST] Migrated Content 2",
    "topics": "[TEST] Topic 1,[TEST] Topic 2",
    "thumbnail": [
      {
        "uuid": "f352fb5f-5319-4a09-a039-6b7080b31443",
        "name": "D10 launch.png",
        "file": "https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png",
        "alt": "Test alt text for thumbnail"
      }
    ],
    "vertical_spacing": "top",
    "hide_sidebar": true,
    "show_last_updated_date": false,
    "last_updated_date": "1 Oct 2022",
    "show_toc": true,
    "banner": {
      "type": "container",
      "children" : [
        {
          "theme": "dark",
          "title": "[TEST] Banner title - Migrated Content 2",
          "banner_type": "default",
          "blend_mode": "darken",
          "featured_image": [
            {
              "uuid": "f352fb5f-5319-4a09-a039-6b7080b31443",
              "name": "D10 launch.png",
              "file": "https://www.civictheme.io/sites/default/files/images/2022-10/D10%20launch.png",
              "alt": "Test alt text for thumbnail"
            }
          ],
          "background": [
            {
              "uuid": "427186ad-c561-4441-9951-28399d8a4923",
              "name": "demo_banner-background.png",
              "file": "https://www.civictheme.io/sites/default/files/demo_banner-background.png",
              "alt": ""
            }
          ],
          "hide_breadcrumb": true
        }
      ]
    }
  }
]


```
