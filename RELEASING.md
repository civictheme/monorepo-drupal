# Releasing

[git-flow](https://danielkummer.github.io/git-flow-cheatsheet/) is used to manage releases.

## Release outcome

1. Release branch exists as `release/X.Y.Z` in GitHub repository.
2. Release tag exists as `X.Y.Z` in GitHub repository.
3. The `HEAD` of the `master` branch has `X.Y.Z` tag.
4. The hash of the `HEAD` of the `master` branch exists in the `develop` branch. This is to ensure that everything pushed to `master` exists in `develop` (in case if `master` had any hot-fixes that not yet have been merged to `develop`).
5. There are no PRs in GitHub related to the release.

## Version Number

Release versions are numbered according to [Semantic Versioning](https://semver.org/).
Given a version number X.Y.Z:
* X = Major release version. No leading zeroes.
* Y = Minor Release version. No leading zeroes.
* Z = Hotfix/patch version. No leading zeroes.

Examples:
* Correct: `0.1.0`, `1.0.0` , `1.0.1` , `1.0.10`
* Incorrect: `0.1` , `1` , `1.0` , `1.0.01` , `1.0.010`

## Multi-repository releases

This repository is a mono-repository, meaning that it contains multiple packages
that are published to other repositories on every commit to `develop` branch.

Releasing to this repository will not create releases in other repositories.
This was done purposefully to avoid creating releases in other repositories
when there are no changes in them and to allow having own versions in published
repositories as changes in this repository do not necessarily mean that there
are changes in published repositories.

Release log of this repository must contain all the changes that were made to
all the packages that are published from this repository.
Parts of the release log can then be manually copied to the release logs of
published repositories.
