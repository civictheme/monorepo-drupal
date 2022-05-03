# Components

CivicTheme Component Library provides 50+ components out of the box with a
comprehensive system to modify, extend and create new components to fit your
needs.

## Modifying components

CivicTheme comes with an extensive variables and colour customisation system to
enable you to change the look and feel.
Please see sections on `colors` and `variables` for instructions on how to
modify components.

## Extending components

Many CivicTheme components of the components come with extendable regions either
through injecting HTML/templates through a variable or by opening up the
component to extension via block regions.

Look at the navigation card component in
`civictheme_starter_theme/components/02-molecules`:

1. `navigation-card.stories.js` - it uses a cloned version of CivicTheme stories for
   navigation but adds in a tag knob.
2. `navigation-card.twig` - it extends the `content_bottom` section of CivicTheme
   navigation card and provides tags.

** Important - please read through the twig file for an explanation of the
different namespace used to access the original unmodified CivicTheme - if you get
an error in storybook while developing the component of "too much recursion then
you have not correctly utilised this special namespace. **

We chose to override navigation-card while extending it, but we could have
equally extended it into a new custom component by choosing a different name.
We have done this as an example of this with:

1. `demo-navigation-card/demo-navigation-card.twig`
2. `demo-navigation-card/demo-navigation-card.stories.js`

It implements the same component as navigation card, but it is now its own
component.

For a more advanced case of extending a component please look to the listing
component and views template.

`civictheme/civictheme-library/components/03-organisms/listing/listing.twig` is a clean
implementation of a listing component providing space for filtering and dynamic
controls.
The views component `civictheme/template/views/views-view.html.twig` extends this
component adding back the Drupalisms that views requires.

## Overriding components

CivicTheme also allows overriding of existing templates to use a new custom component
by overriding you are allowing all of places this component in CivicTheme to use the
new overridden template.

We use an alternate namespace that references the unaltered versions of CivicTheme
components that allow us to extend the original component while at the same time
overriding it.

See in `civictheme_starter_theme` in `02-molecules/navigation-card` we have extended
the original CivicTheme navigation card and overridden the original CivicTheme component
to add tags to navigation tag.

We didn't need to extend and override also, we could have placed an entirely new
component in its place with the same name which would override the CivicTheme
component. When doing so however please be aware of where other components have
a dependency on this component and ensure your new component doesn't cause
unforeseen problems.

** Important note to remember: if you change the variable names or add new
variables then you need to map these in the preprocess functions of your child
theme. **

## New components

CivicTheme child themes also have an easy system for adding new components and
including in the component library and integration with Drupal.

To follow along a demo button component has been created as an example of how to
create a new component in your child theme.

Look at `<your-theme>/components/01-atoms/demo-button` for an example of a
custom new component.

It is made up of four annotated files:
- `demo-button.js` - JS library for the component
- `demo-button.scss` - SCSS styles for the component
- `demo-button.stories.js` - storybook story for the component (so it appears in
  storybook)
- `demo-button.twig` - the actual twig template for the component.

These files have been heavily annotated and can be read for an understanding of
how to setup a new component.

### Key architecture concept

Twig components created with the CivicTheme design system are designed to be
CMS-agnostic. That is they can be used by any application that can use twig
templates. We do not include Drupalisms in our component library and we
recommend keeping this practice with child themes to increase the reusability of
components.


