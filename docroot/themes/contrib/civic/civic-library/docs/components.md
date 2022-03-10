# Components

Civic Component Library provides 50+ components out of the box with a comprehensive system to modify, extend and create
new components to fit your needs.

## Modifying components

Civic comes with an extensive variables and colour customisation system to enable you to change the look and feel.
Please see sections on `colors.md` and `variables` for instructions on how to modify components.

## Extending components

Many civic components of the components come with extendable regions either through injecting html / templates through a
variable or by opening up the component to extension via block regions.

Look at the navigation card component in `civic_starter_theme/components/02-molecules`:

1. `navigation-card.stories.js` - it uses a cloned version of civic stories for navigation but adds in a tag knob.
2. `navigation-card.twig` - it extends the `content_bottom` section of civic navigation card and provides tags. 

** Important - please read through the twig file for an explanation of the different namespace used to access the original
unmodified civic  - if you get an error in storybook while developing the component of "too much recursion then
you have not correctly utilised this special namespace **

## Overriding components

Civic also allows overriding of existing templates to use a new custom component by overriding you are allowing all of
places this component in civic to use the new overridden template.

We use an alternate namespace that references the unaltered / unextended versions of civic components that allow us
to extend the original component while at the same time overriding it.

See in `civic_starter_theme` in `02-molecules/navigation-card` we have extended the original civic navigation card 
and overridden the original civic component to add tags to navigation tag.

We didn't need to extend and override also, we could have placed an entirely new component in it's place with the same
name which would override the civic component. When doing so however please be aware of where other components have a
dependency on this component and ensure your new component doesn't cause unforseen problems.

** Important note to remember: if you change the variable names or add new variables then you need to map these in
the preprocess functions of your child theme **

## New components

Civic child themes also have an easy system for adding new components and including in the component library
and integration with Drupal.

To follow along a demo button component has been created as an example of how to create a new component in your child 
theme.

Look at `<your-theme>/components/01-atoms/demo-button` for an example of a custom new component.

It is made up of four annotated files:
- `demo-button.js` - JS library for the component
- `demo-button.scss` - SCSS styles for the component
- `demo-button.stories.js` - storybook story for the component (so it appears in storybook)
- `demo-button.twig` - the actual twig template for the component.

These files have been heavily annotated and can be read for an understanding of how to setup a new component.

### Connecting with Drupal

After setting up a component and structuring the twig file, you can include this new component in a drupal
template with an include statement.

For example, if you were wanting to utilise the new demo button in your theme as a submit button you may
utilise the already existing civic template  `civic/templates/input--submit.html.twig` and add thie file in your theme
and change the `@atoms/button/button.twig` include to `@atoms/demo-button/demo-button.twig`.

If you need to provide custom variables to your component, these can be derived through the preprocess hook system
Drupal provides.
