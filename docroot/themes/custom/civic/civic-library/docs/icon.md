# Re-generating icons library

Icons library is generated from provided files and stored in `icon_library.twig`
file to avoid constant re-compilation.

When icon set is update, run the script below to update the contents
of `icon_library.twig`:

    npm run generate-icon-library
