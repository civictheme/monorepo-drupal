# Deployment process

This project is deployed to the Salsa Digital hosting Lagoon instance.
Deployments are initiated by the Continuous Integration (CI) process on each
push to the `master`, `develop`, `release/*`, and `feature/*` branches,
provided all tests pass.

## Pull Request (PR) Environments

1. Developers create code on their local machine.
2. The code is then pushed to GitHub and a pull request is opened for the change.
3. The CI process monitors for code changes and initiates an automated build.
4. Once all tests pass at the end of the build, the CI process triggers a
   deployment to Lagoon.
5. A PR environment with the name of the PR is established upon deployment. Any
   pending update hooks and other deployment operations are executed during
   deployment.

When a PR is closed, its environment is automatically removed.

## Content Profile Preview Environments

Content profiles are employed to capture content for industry-specific demo
sites. Each content profile is automatically deployed to a preview environment
during development (hosted on this project's Lagoon instance) and to a
LaunchPad-based Lagoon instance upon release (handled outside the
CivicTheme team).

The development preview environments are redeployed by the CI on each successful
build in the `develop` branch. This ensures the deployability of content profile
sites with each code change.

For a list of demo site URLs for development sites, refer to the main
[README.md](README.md#content-profiles).
