# Components

CivicTheme component library provides 50+ components out of the box with a
comprehensive system to modify, extend and create new components to fit your
needs.

## Modifying components colours

CivicTheme comes with an extensive variables and colour customisation system to
enable you to change the look and feel.
Please see sections on [colors](colors.md) and [variables](variables.md) for
instructions on how to modify components.

## Extending components

Many CivicTheme components come with extendable areas (slots) which can be used
by injecting HTML through pre-defined (empty) variables.

For more advanced use-cases, it is also possible to extend components using
Twig blocks.

## Key architecture concept

Twig components created with the CivicTheme design system are designed to be
CMS-agnostic: they can be used by any application that can use twig templates.

There are no CMS-specific mechanisms used in the Library.
