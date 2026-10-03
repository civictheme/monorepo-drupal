# Fixes since 1.13.0

Range: `1.13.0` (747e6aa9, 2026-05-05) → `origin/develop` (4c3df9ce, 2026-08-24)
Draft release notes: [monorepo-drupal releases](https://github.com/civictheme/monorepo-drupal/releases) (1.14.0 draft)

21 non-merge commits. 11 map to a drupal.org issue; 10 do not.

Every issue number below was resolved against drupal.org, confirmed to be a
CivicTheme issue, and its title and current status read from the page.

## Fixed issues (drupal.org)

| Issue | Title | PR | Commit | d.o status |
|---|---|---|---|---|
| [#3589650](https://www.drupal.org/i/3589650) | Navigation component isn't passed the theme variable | [#1521](https://github.com/civictheme/monorepo-drupal/pull/1521) | `ebdedaa3` | Needs review |
| [#3589150](https://www.drupal.org/i/3589150) | Add post update for fast fact card form display | [#1519](https://github.com/civictheme/monorepo-drupal/pull/1519) | `1aaa7aea` | Active |
| [#3589091](https://www.drupal.org/i/3589091) | Webform container theme suggestion has invalid name † | [#1517](https://github.com/civictheme/monorepo-drupal/pull/1517) | `4e241518` | Active |
| [#3593631](https://www.drupal.org/i/3593631) | Fix encoded (`&amp;`) ampersands showing in breadcrumbs | [#1524](https://github.com/civictheme/monorepo-drupal/pull/1524) | `234b39f9` | RTBC |
| [#3592958](https://www.drupal.org/i/3592958) | Node version in NVM is below minimum for Storybook (→ 22.12.0) | [#1523](https://github.com/civictheme/monorepo-drupal/pull/1523) | `562a931a` | Needs review |
| [#3467058](https://www.drupal.org/i/3467058) | Duplicate `main` elements when 3col layout used in the node display | [#1525](https://github.com/civictheme/monorepo-drupal/pull/1525) | `41d99f3a` | Needs review |
| [#3606009](https://www.drupal.org/i/3606009) | Duplicate trailing breadcrumb item on listing/view pages | [#1529](https://github.com/civictheme/monorepo-drupal/pull/1529) | `e5d2585a` | Needs review |
| [#3609441](https://www.drupal.org/i/3609441) | Update CKEditor styles to override new CKEditor variables | [#1532](https://github.com/civictheme/monorepo-drupal/pull/1532) | `f5e22c4c` | Needs review |
| [#3601881](https://www.drupal.org/i/3601881) | Theme storybook pages don't render | [#1527](https://github.com/civictheme/monorepo-drupal/pull/1527) | `70e4d320` | Needs review |
| [#3614823](https://www.drupal.org/i/3614823) | Footer menu blocks with title show double title | [#1536](https://github.com/civictheme/monorepo-drupal/pull/1536) | `c9eefa07` | Needs review |
| [#3612955](https://www.drupal.org/i/3612955) | Custom fonts served from assets path do not show in storybook | [#1534](https://github.com/civictheme/monorepo-drupal/pull/1534) | `b373237e` | RTBC |

† #3589091 was found by searching the queue, not from the commit — PR #1517 and
its commit subject reference no issue, so it is missing from the release notes'
issue trail. Worth adding a comment on the issue pointing at the PR.

**Release housekeeping:** not one of the 11 is set to Fixed / Closed (fixed) on
drupal.org, though all the code is on `develop`. They all need a status flip as
part of cutting 1.14.0.

## Undocumented fixes — issues to create

I searched the CivicTheme queue (`status=All`) for each of these before listing
it: `webform container`, `side navigation`, `sidebar`, `filter_url`,
`provision`, `pathauto`, `attribute`, `11.4`, `storybook`, `deploy`. The only
hit was #3589091 above, so the six below have no existing issue.

Tracker: `d.o` = drupal.org/project/civictheme, `uikit` =
github.com/civictheme/uikit, `monorepo` = github.com/civictheme/monorepo-drupal.

| # | Proposed issue | Tracker | Where it landed | Why it needs an issue |
|---|---|---|---|---|
| 1 | Side navigation block renders the block label as well as its own title | d.o | `9ab59004` / [#1540](https://github.com/civictheme/monorepo-drupal/pull/1540) | Tracked only as [civictheme/uikit#1000](https://github.com/civictheme/uikit/issues/1000). The subject reads `Issue #1000` in drupal.org format, so it will be misread as a d.o issue — and `drupal.org/i/1000` points at an unrelated core node. Theme-side fix (passes `title` into `civictheme:side-navigation`, unsets the block label) with no d.o record. |
| 2 | Deprecated `_filter_url()` in URL-to-link processing | d.o | `d1fc3d89` / [#1538](https://github.com/civictheme/monorepo-drupal/pull/1538) | `includes/link.inc` now calls `$filter->process(...)->getProcessedText()`. Deprecated in 11.4.0, removed in 13.0.0 ([change record](https://www.drupal.org/node/3566774)) — fires a deprecation notice on every call on 11.4 and fails deprecation-strict test runs. |
| 3 | `Undefined property: Drupal\Core\Extension\Theme::$subpath` during provisioning | d.o | `d1fc3d89` / [#1538](https://github.com/civictheme/monorepo-drupal/pull/1538) | `theme-settings.provision.inc` switched to `->getPath()`. PR body names this exact error, so it was observed in the wild; affects subtheme provisioning / optional config install. |
| 4 | Stray `uuid` key removed from `selection_criteria` in pathauto pattern config | d.o | `d1fc3d89` / [#1538](https://github.com/civictheme/monorepo-drupal/pull/1538) | Key dropped from `pathauto.pattern.civictheme_{alert,event,page}.yml`. Cause not stated in the PR — likely config schema strictness, unconfirmed. Existing sites keep the key unless a post-update is written; that decision belongs on an issue. |
| 5 | Deprecated `user_load_by_name()` in generated content user creation | monorepo | `d1fc3d89` / [#1538](https://github.com/civictheme/monorepo-drupal/pull/1538) | `cs_generated_content/generated_content/user/user.inc` now uses `loadByProperties()`. Deprecated in 11.4.0, removed in 13.0.0 ([change record](https://www.drupal.org/node/3555936)). Monorepo-only module, so GitHub rather than d.o. |
| 6 | Storybook emits a bogus `_keys` HTML attribute on every `create_attribute` element | d.o or uikit | `6d9cb6d9` / [#1537](https://github.com/civictheme/monorepo-drupal/pull/1537) | Twig.js stamped compiled object literals with `_keys`, rendered as `_keys="data-skip-to-target"` in markup. Fixed by overriding `drupal-attribute` with `@civictheme/drupal-attribute@2.0.1` and SHA-pinning the `drupal-twig-extensions` fork (also clears a locutus 3 advisory). Changes attribute order, empty-value rendering and escaping — downstream snapshots need review, which is exactly what an issue should record. Tracker depends on where the forks are maintained. |

## Chore commits — no issue needed

| Commit | PR | What | Why no issue |
|---|---|---|---|
| `4c3df9ce` | [#1542](https://github.com/civictheme/monorepo-drupal/pull/1542) | build-deploy GitHub Actions moved to Node 24 runtimes (8 action bumps) | CI maintenance |
| `ac0a13df` | [#1541](https://github.com/civictheme/monorepo-drupal/pull/1541) | UI Kit bumped to latest `main` | Dependency bump; changes tracked in the UI Kit repo |
| `d1fc3d89` | [#1538](https://github.com/civictheme/monorepo-drupal/pull/1538) | Drupal 11.3.10 → 11.4.5, minimum supported version raised to Drupal 10.6, core patch 14337 re-rolled per branch, CI matrix now 10.6 + 11.4, Drupal Extension / Behat updates, new subtheme-creation unit test | Version bump is a chore — the four code fixes bundled inside it are items 2–5 above |
| `70648dfa` | — | `composer.lock` Drupal version bump | Dependency bump |
| `2f6c578f` | [#1526](https://github.com/civictheme/monorepo-drupal/pull/1526) | phpstan fixes, rector rule removal, `ThreeColumnsLayout` cleanup | Static-analysis cleanup ([#3520413](https://www.drupal.org/i/3520413) covered this ground and is closed) |
| `bf4fe7fc` | [#1522](https://github.com/civictheme/monorepo-drupal/pull/1522) | Lint fixes across theme includes; phpcs / phpstan / twig-cs config | Static-analysis cleanup |
| `697ec09f` | [#1518](https://github.com/civictheme/monorepo-drupal/pull/1518) | Lint fix in `automated_list.inc` | One-line cleanup |
| `a77b2657` | — | Lint fixes (eslint config, settings form section) | Static-analysis cleanup |

## Two other things worth catching before release

1. **`70648dfa` and `a77b2657` landed on `develop` without a PR**, so the
   release-drafter draft omits them entirely. Neither is user-facing, but the
   changelog is incomplete unless they're added by hand.
2. **The deploy workflow has been red since 2026-05-15** — `Initialize Quant
   Cloud` fails with `Organization '***' does not exist or is not accessible`.
   PR #1542 establishes this is not the Node runtime issue it bumps: the Quant
   API is answering with a 404 on the organisation lookup, and the credentials
   have been unchanged since 2025-09-14. That needs its own monorepo issue and
   a check in the Quant Cloud dashboard — it is unresolved, not fixed.

---

This file is untracked at the repo root while the branch is
`feature/gha-node24-remaining-workflows` — don't let it ride along in a
`git add .`.
