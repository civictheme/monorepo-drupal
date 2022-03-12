# Icons

Civic Component Library provides an expansive range of icons that are open
source and can be re-used within the child theme.

** Important note: Icons must be manually included within the child theme to
be utilised**

## Including icons within the child theme

Civic Component Library stores icons in the `civic-library/assets/icons`
directory split into subdirectory libraries.

Copy and paste the icon svg file you wish to include in the starter kit from
their `civic-library/assets/icons/<icon-pack>` icon
pack directory into their respective `<child-theme>/assets/icons/<icon-pack>`
directory.

** Important note icons in child themes must be in the corresponding <icon-pack>
directory of Civic library otherwise Civic templates won't be able to reference
the required icon. **

We recommend not deleting any of the default icons found in the starter theme as
these are used by Civic theme in the default templates.

## Re-generating icons library

Icons library is generated from provided files and stored in `icon_library.twig`
file to avoid constant re-compilation.

After you have finished including the icons you wish to use in your child theme,
run the script below to update the contents of `icon_library.twig`:

    npm run generate-icon-library

## Troubleshooting icons

If you are experiencing a missing icon in your drupal webpage and the SVG tag is
not being rendered ie `div.civic-icon` does not have any inner SVG element -
this is caused by the icon svg missing from your child theme. Follow the above
process to include it.
