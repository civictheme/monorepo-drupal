# Civic Component Library

Civic component library with Storybook integration.

## Features

- Atomic design
- Accessible
- Platform-agnostic
- Drupal and Wordpress compatible

## Installing

    npm install

## Building

    npm run build

This will build:

- CSS and JS assets in the `dist` directory. These files can be included
  directly into your HTML page, provided that it has components implemented with
  the same markup as components in the `componets` directory.
- Storybook assets as compiled HTML page in the `storybook-static` directory.
  These files can be served publically to show all components available in the
  library.

## Linting

    npm run lint

    npm run lint:fix

## Running locally

    npm run storybook

## Documentation

Please refer to dedicated [Documentation](docs/introduction.md).
