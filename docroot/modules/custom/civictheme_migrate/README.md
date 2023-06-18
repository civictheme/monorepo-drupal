# CivicTheme Migrate

A Drupal module providing pre-defined CivicTheme mappings for performing
migrations from URLs or files in JSON format.

## How it works

This module includes `migrate_plus` [migration configurations](config/install)
for CivicTheme entities.

Upon installation, the migration configurations are copied into the site's
configurations.

You can then edit each migration
at `admin/structure/migrate/manage/civictheme_migrate/migrations/<migration_name>/edit`
to provide sources, such as URLs or uploaded files (a combination of both is
also supported). Once the migration is updated, the newly discovered content
from the provided sources is available for import.

## Migration file structure (schema) validation

This module offers migration schemas for validating the structure of uploaded
files. See the [schema README.md](schemas/README.md) for more
details.

## Remote authentication

If your migration soures are remote URLs and require authentication, you can
configure it at the `/admin/config/civictheme-migrate` page.

Currently, only basic authentication is supported.

## Maintenance

The mappings with third-party services are documented in
the [MAPPINGS.md](MAPPINGS.md) file. These documented mappings are reflected in
both [migration configurations](config/install)
and [migration schemas](schemas).

When creating a new migration configuration, follow these guidelines:

1. Create the migration as a standard `migrate_plus` migration and place it
   into `config/install`.
2. Name the migration in the `<entity_type>_<bundle>` format, resulting in a
   configuration file named `migrate_plus.migration.<entity_type>_<bundle>.yml`.
3. Set the `source.urls` property to an empty array (`[]`).
4. Add a new data set into a [Kernel test](tests/src/Kernel/MigrationFileValidatorKernelTest.php)
   and a [fixture](tests/fixtures) file to make sure that the provided schema
   validates correctly.
