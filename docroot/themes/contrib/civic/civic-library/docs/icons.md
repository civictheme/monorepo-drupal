# Icons

Civic Component Library provides an expansive range of icons that are open source and can be re-used within
the child theme.

** Important note: Icons must be manually included within the child theme to be utilised**

## Including icons within the child theme

Civic Component Library stores icons in the `civic-library/assets/icons` directory split into subdirectory libraries.

Available icons can be viewed

In your generated child theme, you wi


# Re-generating icons library

Icons library is generated from provided files and stored in `icon_library.twig`
file to avoid constant re-compilation.

When icon set is update, run the script below to update the contents
of `icon_library.twig`:

    npm run generate-icon-library
