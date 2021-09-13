Civic Drupal theme
==================

Based on Civic Node components.

Development workflow
--------------------
This lists 2 development workflows:
- Stable - Civic Node is released to NPM registry; Civic Drupal theme has stable release
- Development - Civic Node is being developed within Civic Drupal theme; Civic Drupal theme in active development

### Stable
1. Civic theme required by composer (if open-sourced) OR copied from Salsa's GitHub repo.
  1. Civic theme comes with `civic-node` components pre-packaged. This is to make sure that Civic Drupal theme is a simple "drop-in" solution and does not require any build steps.
  2. Civic theme can be used as-is (primarily - for demo purposes).
  3. Civic theme has own Storybook compiled and served within theme settings (see below).
  4. Civic theme will not have adjustable settings in Drupal at the beginning. But later, it will be possible to adjust colours, font-size and other styles through Theme configuration. This is delayed to get Civic theme to market ASAP.
  5. Civic theme will later have a starter kit that will allow to create a sub-theme. This is delayed to get Civic theme to market ASAP.
  6. Civic theme will have templates for all standard Drupal elements as components.
  7. Civic theme will have Drupal back-end components (paragraphs) provided to create all required content structures.
2. Civic theme will need to be enabled and can be used as-is.
3. When using custom Consumer theme based on Civic:
  1. Consumer theme will use Civic theme as a base theme.
  2. Consumer theme has all configuration setup to use components from `@civic` or `@custom` namespaces. Note that the consumer theme can override any component in `@civic` namespace by naming the file in the same way (the namespace will stay `@civic` - child->parent lookup is a courtesy of `components` Drupal module).
  3. Consumer theme will have the scripts to add Twig, SASS and JS outside of components and compile them together with components.
  4. Consumer theme will have own Storybook that will contain components from Civic theme and itself.
  5. If the NodeJS build is not supported in the hosting environment - consumer theme's developer will have to compile assets locally and commit compiled assets to the repository. This is easily controlled by a single entry in Consumer theme's `.gitignore`.
4. Consumer theme will need to be enabled and set as default.

### Development
Everything above applies, except for:
1. Civic Node is a part of Civic Drupal theme repository until it gets full maturity. `./components` to `./civic-node/components`. Once in stable phase - a build script will be:
  1. Requiring Civic Node from NPM
  2. Compiling assets
  3. Copying `./civic-node/components` to `./components`
  4. Creating `storybook-static` which will be packaged to Civic theme
2. For now, Civic theme is a part of this repo. If a decision is made to extract it:
  1. Civic theme will be required by Composer as a private package using GitHub token that will be committed to this repo.
  2. Civic theme repo will still need to have a "reference site" (similar to this one) to show how components are working.

## Usage
Install

    npm install

Build

    npm run build

Storybook

    npm run storybook
