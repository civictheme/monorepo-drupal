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
    "title": "[TEST] Title 1",
    "id": "1",
    "alias": "/test-title1"
  },
  {
    "title": "[TEST] Title 2",
    "id": "2",
    "alias": "/test-title2"
  },
  {
    "title": "[TEST] Title 3",
    "id": "3",
    "alias": "/test-title3"
  }
]

```
