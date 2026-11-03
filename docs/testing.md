# Testing

## Authoring tests

This repository uses different types of tests:
1. Unit, Functional and Kernel tests for Drupal modules and themes.
2. Bats tests to test tooling and scripts.
3. Behat tests to test overall end-to-end Drupal site functionality.

### Behat tests

Behat configuration uses multiple extensions:
- [Drupal Behat Extension](https://github.com/jhedstrom/drupalextension) - Drupal integration layer. Allows to work with Drupal API from within step definitions.
- [Behat Screenshot Extension](https://github.com/integratedexperts/behat-screenshot) - Behat extension and a step definition to create HTML and image screenshots on demand or test fail.
- [Behat Progress Fail Output Extension](https://github.com/integratedexperts/behat-format-progress-fail) - Behat output formatter to show progress as TAP and fail messages inline. Useful to get feedback about failed tests while continuing test run.
- `FeatureContext` - Site-specific context with custom step definitions.

Add `@skipped` tag to failing tests if you would like to skip them.

### Alert REST export regression checks

When changing the alert REST export, test visibility values with multiple lines,
literal `<front>`, and ampersands. Also test an empty visibility value. The
visibility field must use raw output so these path values reach the client
without HTML escaping or markup.

### Alert REST export

The alert REST display must return all active alerts before the browser applies
page visibility rules. When checking this behaviour, create more than 10 active
alerts targeting different pages and verify that every eligible alert is present
in `/api/civictheme-alerts`, including alerts outside the first 10 results.
