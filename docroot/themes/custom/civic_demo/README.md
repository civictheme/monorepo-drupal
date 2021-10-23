Civic Demo Drupal theme
=======================

Based on Civic Drupal theme

Development workflow
--------------------
This lists 2 development workflows:

- Stable - Civic Library is released to NPM registry; Civic Drupal theme has
  stable release
- Development - Civic Library is being developed within Civic Drupal theme;
  Civic Drupal theme in active development

1. Uses Civic theme as a base theme.
2. Has all configuration setup to use components from `@civic` or `@custom`
   namespaces. Note that the consumer theme can override any component
   in `@civic` namespace by naming the file in the same way (the namespace will
   stay `@civic` - child->parent lookup is a courtesy of `components` Drupal
   module).
3. Has the scripts to add Twig, SASS and JS outside of components and compile
   them together with components.
4. Has own Storybook that will contain components from Civic theme and itself.
5. If the NodeJS build is not supported in the hosting environment - consumer
   theme's developer will have to compile assets locally and commit compiled
   assets to the repository. This is easily controlled by a single entry in
   Consumer theme's `.gitignore`.

## Usage

Install

    npm install

Build

    npm run build

Storybook

    npm run storybook
