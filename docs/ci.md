# Continuous Integration

In software engineering, continuous integration (CI) is the practice of merging
all developer working copies to a shared mainline several times a day.
Before feature changes can be merged into a shared mainline, a complete build
must run and pass all tests on CI server.

This project uses [GitHub Actions](https://github.com/features/actions) as a CI
server: the `CI` workflow builds the codebase and runs code linting and tests
for every push. Deployments run in the separate `Build and Push` workflow,
which waits for a green `CI` run on the commit before deploying nominated
branches (usually `main`, `develop` and `2.x`) to Quant Cloud.

## Skipping CI build

Add `[skip ci]` to the commit subject to skip CI build. Useful for documentation
changes.

## Build types

In order to maintain the software quality we need to make sure that the code can run:
1. On the current and next version of bare Drupal core
2. On the current version of GovCMS
3. As a standalone theme, sub-theme as a sibling and sub-theme as a custom theme
4. On current and next version of PHP
5. For content profiles
6. For the modules we publish

Having a full matrix of all variants is not feasible, so we run 2 types of builds:
1. **Isolated** - installs the theme code on a fresh bare Drupal site using the build scripts from https://github.com/AlexSkrypnyk/drupal_extension_scaffold
2. **Full build** - runs as if the theme code was installed on a `minimnal` or `govcms` profile with all custom modules enabled using the Salsa Hosting scaffold build
