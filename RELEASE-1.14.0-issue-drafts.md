# Issue drafts for the undocumented 1.14.0 fixes

Six retrospective issues — the code has already landed on `develop`, so each
one is filed to create a findable record, then moved straight to
**Needs review** (or **Fixed**, if you're closing them out with the release).

Suggested field values for all drupal.org tickets, matching how #3614823 was
filed: **Version** `1.13.0` · **Component** `Code` · **Category** `Bug report`
· **Priority** `Normal`.

Two are not drupal.org tickets:

- **#5** belongs on `civictheme/monorepo-drupal` — `cs_generated_content` only
  exists in the monorepo, not in the contrib theme.
- **#6** is d.o *or* `civictheme/uikit`, depending on where the npm forks are
  maintained.

Corrections to my earlier report, from checking the 11.4.5 core in this build:
`_filter_url()` and `user_load_by_name()` are **deprecated in 11.4.0 and removed
in 13.0.0** — they don't fatal on 11.4, they emit deprecation notices. Only
`Extension::$subpath` is actually gone.

---

## 1. Sidebar navigation block title renders as a plain block heading instead of the component title

*Tracker: drupal.org/project/civictheme · Category: Bug report*

### Problem/Motivation

`templates/block/block.html.twig` renders `<h2{{ title_attributes }}>{{ label }}</h2>`
whenever a block has "Display title" enabled.
`block--menu-block--civictheme-sidebar-navigation.html.twig` did not pass
`title` into the `civictheme:side-navigation` include, so the heading was
emitted as the generic block `<h2>` outside the component markup while the
component's own title slot stayed empty. The heading therefore did not match
the design.

Same root cause as [#3614823](https://www.drupal.org/i/3614823) (footer menu
blocks showing a double title) — the generic `block.html.twig` label competing
with a CivicTheme component that renders its own heading — but on a different
block, and here the component was never given the title at all.

Currently tracked only as [civictheme/uikit#1000](https://github.com/civictheme/uikit/issues/1000),
with no drupal.org record. The merge commit's subject reads
`Issue #1000 by sonictruth: …`, which follows the drupal.org convention and so
reads as a d.o issue number — `drupal.org/i/1000` is an unrelated core node.

### Steps to reproduce

1. Place the CivicTheme sidebar navigation menu block in a sidebar region.
2. Edit the block and enable **Display title**.
3. View a page with the sidebar.

The heading renders as a bare `<h2>` from `block.html.twig`, outside the
`ct-sidebar-navigation` component markup and without the component's styling.

### Proposed resolution

In `includes/sidebar_navigation.inc`, inside
`civictheme_preprocess_block__menu_block__civictheme_sidebar_navigation()`, set
`$variables['label'] = ''` so the generic block heading is suppressed, and pass
`title: title` into the `civictheme:side-navigation` include in
`templates/block/block--menu-block--civictheme-sidebar-navigation.html.twig`.
`$variables['title']` was already being populated from
`configuration.label_display`, it just wasn't reaching the component.

### Remaining tasks

Fixed on `develop` in `9ab59004` ([PR #1540](https://github.com/civictheme/monorepo-drupal/pull/1540)).

Worth doing as a follow-up: audit the remaining block templates that render a
CivicTheme component for the same conflict. #3614823 fixed footer menu blocks
with a targeted `$variables['label'] = ''` in a new `includes/footer.inc`, and
this issue fixes the sidebar; a generic approach may be better than a third
one-off.

### User interface changes

Yes — the sidebar navigation heading now uses the side navigation component's
title styling instead of the theme's generic block heading.

### API changes

None.

### Data model changes

None.

---

## 2. Replace deprecated `_filter_url()` in `_civictheme_process_html_content_urls_to_links()`

*Tracker: drupal.org/project/civictheme · Category: Bug report*

### Problem/Motivation

`_filter_url()` and its helper functions are deprecated in Drupal 11.4.0 and
removed in Drupal 13.0.0. The logic moved into
`Drupal\filter\Plugin\Filter\FilterUrl::process()` and no procedural
replacement is provided — change record:
[Various filter module procedural functions are deprecated](https://www.drupal.org/node/3566774).

`includes/link.inc` called `_filter_url($html, $filter)` in two places inside
`_civictheme_process_html_content_urls_to_links()` — once for a configured
format's `filter_url` instance and once for the default fallback instance. On
Drupal 11.4 both paths emit a deprecation notice on every call, which fails any
test run that treats deprecations as errors, and both will fatal on Drupal 13.

Verified against the 11.4.5 core in this build: the function still exists and is
deprecated, not removed.

### Steps to reproduce

On Drupal 11.4 with deprecation reporting enabled, render content that goes
through `_civictheme_process_html_content_urls_to_links()` — for example a
plain-text field containing a bare URL — and observe the deprecation notice.

### Proposed resolution

Call the filter plugin directly in both places:

```php
$filter->process($html, LanguageInterface::LANGCODE_NOT_SPECIFIED)->getProcessedText()
```

with `use Drupal\Core\Language\LanguageInterface;` added.

### Remaining tasks

Fixed on `develop` in `d1fc3d89`
([PR #1538](https://github.com/civictheme/monorepo-drupal/pull/1538)), bundled
into the Drupal 11.4.5 update. Filed separately so sites still on 1.13.x can
find it.

### User interface changes

None. `FilterUrl::process()` runs the same logic that was extracted from
`_filter_url()`.

### API changes

None public.

### Data model changes

None.

---

## 3. `Undefined property: Drupal\Core\Extension\Theme::$subpath` in `_civictheme_get_theme_dependencies()`

*Tracker: drupal.org/project/civictheme · Category: Bug report*

### Problem/Motivation

`_civictheme_get_theme_dependencies()` in `theme-settings.provision.inc` read
`$theme_data[$theme_name]->subpath` to locate a theme's `config/optional`
directory. `subpath` is not a declared property of
`Drupal\Core\Extension\Extension` — it is an undocumented dynamic property that
`ExtensionDiscovery::scanDirectory()` assigns while scanning, which is why the
line carried a `@phpstan-ignore-next-line` suppression.

On Drupal 11.4 this produces:

```
Undefined property: Drupal\Core\Extension\Theme::$subpath
```

reported during theme provisioning in
[PR #1538](https://github.com/civictheme/monorepo-drupal/pull/1538). The
optional-config dependency scan then fails, so dependencies declared only via
`config/optional` are not collected.

The class name in that error is the tell. `ExtensionList::doList()` now runs
every scanned extension through `subClassExtension()`, and
`ThemeExtensionList::subClassExtension()` returns a **brand new**
`Drupal\Core\Extension\Theme` object:

```php
protected function subClassExtension(Extension $extension): Theme {
  return new Theme($this->root, $extension->getPathname(), $extension->info, $extension->getExtensionFilename());
}
```

Only root, pathname, info and filename carry over — every dynamic property the
discovery scan set, `subpath` included, is discarded. So the property is still
assigned during discovery and still survives the extension-list cache
(`Extension::__sleep()` uses `get_object_vars()`), but it no longer exists on
the objects `extension.list.theme` hands back. Verified against the 11.4.5 core
in this build.

### Steps to reproduce

On Drupal 11.4, run CivicTheme provisioning for a theme — theme settings →
provision content, or `civictheme_provision_cli()` — and watch for the
undefined property error while dependencies are resolved.

### Proposed resolution

Use the supported API on the `Extension` object:

```php
$theme_path = $theme_data[$theme_name]->getPath();
```

and drop the now-unnecessary `@phpstan-ignore-next-line`.

### Remaining tasks

Fixed on `develop` in `d1fc3d89` (PR #1538).

Follow-up: the same function still reads
`$theme_data['civictheme']->module_dependencies` behind another
`@phpstan-ignore-next-line`, which is a dynamic property added by
`ThemeExtensionList` (core carries a `@todo` about exactly this). Worth
auditing all `@phpstan-ignore` suppressions that lean on dynamic `Extension`
properties before the next core minor removes another one.

### User interface changes

None.

### API changes

None.

### Data model changes

None.

---

## 4. Remove stray `uuid` key from `selection_criteria` in pathauto pattern config

*Tracker: drupal.org/project/civictheme · Category: Bug report*

### Problem/Motivation

The three shipped pathauto patterns —
`config/install/pathauto.pattern.civictheme_alert.yml`,
`…civictheme_event.yml` and `…civictheme_page.yml` — each carried a `uuid` key
*inside* their `selection_criteria` entry, duplicating the UUID already used as
that entry's array key:

```yaml
selection_criteria:
  47608ecb-ac88-4819-b6e1-9a517f094bd8:
    id: 'entity_bundle:node'
    negate: false
    uuid: 47608ecb-ac88-4819-b6e1-9a517f094bd8   # <- removed
    context_mapping:
      node: node
```

The key is not part of the condition plugin's configuration schema. It was
removed as part of the Drupal 11.4.5 update, but the reason was not recorded in
[PR #1538](https://github.com/civictheme/monorepo-drupal/pull/1538) — most
likely config schema validation tightening, which is **unconfirmed** and should
be established on this issue rather than assumed.

### Steps to reproduce

Install CivicTheme and run config schema validation over the installed
configuration — a kernel test with `ConfigSchemaChecker` enabled, or
`drush config:inspect` — and inspect
`pathauto.pattern.civictheme_alert` and friends.

### Proposed resolution

Drop the `uuid` line from the three `config/install` files.

### Remaining tasks

Landed on `develop` in `d1fc3d89` (PR #1538), but the issue is not finished:

1. Confirm and record the actual failure mode the key caused.
2. Decide whether existing sites need a `post_update` hook to strip the key
   from active config. Editing `config/install` only affects fresh installs —
   every site installed before 1.14.0 still carries the key.
3. Confirmed already: these three were the only pathauto pattern files in the
   theme, and no `uuid` key remains inside `selection_criteria` in any of them.

### User interface changes

None.

### API changes

None.

### Data model changes

Configuration only, on new installs. Existing sites are unchanged unless task 2
above adds an update hook.

---

## 5. Replace deprecated `user_load_by_name()` in the `cs_generated_content` user generator

*Tracker: **github.com/civictheme/monorepo-drupal** — not drupal.org.
`cs_generated_content` is a monorepo-only custom module and does not ship with
the contrib theme.*

### Problem

`web/modules/custom/cs_generated_content/generated_content/user/user.inc`
called `user_load_by_name()`, deprecated in Drupal 11.4.0 and removed in
13.0.0. The change record is
[user_load_by_name() and user_load_by_mail() are deprecated](https://www.drupal.org/node/3555936);
the replacement is
`\Drupal::entityTypeManager()->getStorage('user')->loadByProperties()`.

Secondary problem in the same code: it loaded the account by name, then
re-loaded it by ID from storage just to delete it, and only ever handled a
single match.

### Steps to reproduce

Run generated content provisioning on Drupal 11.4 with deprecations reported —
`cs_generated_content_generated_content_create_user_user()` fires the notice for
every role and index it iterates.

### Proposed resolution

```php
$user_storage = \Drupal::entityTypeManager()->getStorage('user');
$existing_users = $user_storage->loadByProperties(['name' => $name]);
foreach ($existing_users as $existing_user) {
  $existing_user->delete();
}
```

This drops the redundant re-load and deletes every match rather than one.

### Remaining tasks

Fixed on `develop` in `d1fc3d89` (PR #1538). Worth a sweep of the rest of the
monorepo's custom modules for other 11.4 deprecations before Drupal 13.

### User interface changes

None — development tooling only.

### Data model changes

None.

---

## 6. Storybook renders a spurious `_keys` attribute on elements built with `create_attribute()`

*Tracker: drupal.org/project/civictheme, or civictheme/uikit if the npm forks
live there · Category: Bug report*

### Problem/Motivation

In the Storybook / Vite pipeline, `drupal-twig-extensions` implements
`create_attribute()` on top of the upstream `drupal-attribute` class. Twig.js
stamps every compiled object literal with an internal `_keys` array, and
upstream `drupal-attribute` renders that array as an HTML attribute:

```html
<!-- before --> <a href="#top" data-skip-to-target="" _keys="data-skip-to-target">
<!-- after -->  <a href="#top" data-skip-to-target="">
```

Upstream `create_attribute()` also reads `Object.keys()`, so rendered attribute
order does not follow the template. Both are visible in Storybook output for
every component that uses `create_attribute` — all 26 call sites were rendered
through the Vite pipeline to confirm the failure before the change.

### Steps to reproduce

1. Build Storybook for the theme or the starter kit before the fix.
2. Inspect any component that uses `create_attribute` — the skip link is the
   easiest.
3. `_keys` appears as an attribute in the rendered markup.

### Proposed resolution

As landed in [PR #1537](https://github.com/civictheme/monorepo-drupal/pull/1537):

1. Override the transitive `drupal-attribute` with
   `@civictheme/drupal-attribute@2.0.1`. An `overrides` entry is required —
   `2.x` does not satisfy the `^1.0.2` its dependents pin.
2. Register `createAttribute()` with Twig.js in both `.storybook/preview.js`
   files so attributes keep template order. This has to patch
   `Twig.extendFunction` rather than register once, because every compiled Twig
   module re-runs `addDrupalExtensions(Twig)` on import and would otherwise
   re-register the unfixed version.
3. Point `drupal-twig-extensions` and `vite-plugin-twig-drupal` at the
   SHA/tag-pinned CivicTheme forks — locutus 3 (clears a critical advisory) and
   Twig.js 3 support.

### Remaining tasks

Fixed on `develop` in `6d9cb6d9` (PR #1537). Still open:

- Rendered markup changes beyond `_keys`: attribute order now follows the
  template, empty values render as `key=""`, and `toString()` escapes with
  `Html::escape()` parity — matching Drupal's PHP `Attribute`. Downstream
  snapshot tests need reviewing, not blind re-baselining.
- Decide the long-term home for the three forks
  (`@civictheme/drupal-attribute`, `civictheme/drupal-twig-extensions`,
  `civictheme/vite-plugin-twig-drupal`): upstream the fixes, or accept
  permanently pinned forks that need maintaining on every dependency bump.

### User interface changes

Rendered component markup in Storybook changes as described above. No change to
Drupal-rendered output, which uses PHP `Attribute`.

### API changes

None.

### Data model changes

None.
