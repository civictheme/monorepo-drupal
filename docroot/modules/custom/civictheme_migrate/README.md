# CivicTheme Migrate

Merlin Migration is imported via configuration form (`/admin/configuration/civictheme-migrate`).

You can choose to manually upload the Merlin UI extracted content JSON files or retrieve the extracted content JSON files from Merlin UI API endpoint.

After configuring a migration you can then begin the migration and setup your CivicTheme site.

## Extracted Content Validation

Extracted Content JSON files are validated using [JSON Schema](https://json-schema.org). The schema used in validating
the imported JSON files are located at `civictheme_migrate/schema/civictheme_migrate.extracted_content.schema.json`

For an understanding of the structure of JSON Schema files please [read](https://opis.io/json-schema/2.x/).

Validation is carried out using [opis/json-schema](https://github.com/opis/json-schema).
