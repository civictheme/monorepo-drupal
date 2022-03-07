# Components

Civic Component Library provides 50+ components out of the box with a comprehensive system to modify, extend and create
new components to fit your needs.

## Modifying components

Civic comes with an extensive variables and colour customisation system to enable you to change the look and feel.
Please see sections on `colors.md` and `variables` for instructions on how to modify components.

## Extending components

Many civic components of the components come with extendable regions either through injecting html / templates through a
variable or by opening up the component to extension via block regions.



## Overriding components

Civic also allows overriding of existing templates to use a new custom component by overriding you are allowing all of
places this component in civic to use the new overridden template.

See in `civic_starter_theme` in `02-molecules/navigation-card` we have extended the civic navigation card.



** Important note to remember: if you change the variable names or add new variables then you need to map these in
the preprocess functions of your child theme **

## New components

Civic child themes also have an easy system for adding new components and including in the component library
and integration with Drupal.

To follow along a demo button component has been created as an example of how to create a new component in your child 
theme.

Look at `components/01-atoms/demo-button` for the example.

It is made up of four annotated files:
- `demo-button.js` - JS library for the component
- `demo-button.scss` - SCSS styles for the component
- `demo-button.stories.js` - storybook story for the component (so it appears in storybook)
- `demo-button.twig` - the actual twig template for the component.
