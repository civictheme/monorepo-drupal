`icon_library.twig` has to be re-generated for each custom site based on
the icons used. This is done to avoid bloat of having unused icons.

Add/remove icons from `assets/icons` as required and run

    npm run generate-icon-library

Icons in `icon_library.twig` will be available in Storybook and limited to
assets in `assets/icons`.
