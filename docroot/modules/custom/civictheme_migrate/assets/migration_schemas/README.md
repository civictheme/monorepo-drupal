# Migration Schemas

This directory contains [JSON Schema](https://json-schema.org)-based schemas for
validating migration source files.

Developers of CivicTheme should update these schemas when data models change.

## Naming Convention

To be automatically discovered, the schema files must follow the naming
pattern `<entity_type>_<bundle>.json`.
