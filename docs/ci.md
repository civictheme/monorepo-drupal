# Continuous Integration

In software engineering, continuous integration (CI) is the practice of merging
all developer working copies to a shared mainline several times a day.
Before feature changes can be merged into a shared mainline, a complete build
must run and pass all tests on CI server.

This project uses [Circle CI](https://circleci.com/) as a CI server: it imports
production backups into fully built codebase and runs code linting and tests.
When tests pass, a deployment process is triggered for nominated branches
(usually, `main` and `develop`).

Refer to https://docs.drevops.com/latest/usage/ci for more information.

## Skipping CI build

Add `[skip ci]` to the commit subject to skip CI build. Useful for documentation
changes.

## SSH

Circle CI supports shell access to the build for 120 minutes after the build is
finished when the build is started with SSH support. Use "Rerun job with SSH"
button in Circle CI UI to start build with SSH support.

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
2. **Full build** - runs as if the theme code was installed on a `minimnal` or `govcms` profile with all custom modules enabled using the build scripts from https://github.com/drevops/scaffold
