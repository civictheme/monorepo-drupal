# CivicTheme Canvas Starter

Canvas overlay starterkit, nested inside the main CivicTheme kit. A Canvas
site generates a theme pair — the main theme from the main kit and a thin
overlay from this kit — and owns both:

```bash
php core/scripts/dr generate-theme alpine_shire --name "Alpine Shire" \
  --starterkit <main_starterkit_machine_name> --path themes/custom
php core/scripts/dr generate-theme alpine_shire_canvas --name "Alpine Shire Canvas" \
  --starterkit <canvas_starterkit_machine_name> --path themes/custom
```

The overlay's machine name must be the main theme's machine name plus
`_canvas`: the kit's post-processor derives the main theme's name from it and
rewrites `base theme:` and every component `replaces:` target to the main
theme. The generated overlay ends up with `base theme: alpine_shire`.

Enable the overlay as the site's default theme; it inherits everything from
the main theme and carries only Canvas-specific overrides.

## Component overrides

Overrides live in `components/`, one directory per component. Each override's
`*.component.yml` declares which main-kit component it replaces:

```yaml
name: Button
status: stable
replaces: 'civictheme_starter:button'
```

Drupal's component negotiator serves the override whenever the overlay (or a
theme based on it) is the active theme; same-name magic is not involved — the
`replaces` key is the mechanism. The kit ships near-empty: overrides land
here as Canvas needs emerge.

Reference the main kit by its machine name (`civictheme_starter`) only in
files meant to ship: the post-processor maps machine-name patterns to the
generated main theme. Do not add dot files to this kit — core's generation
skips them when renaming, and unlike the main kit there is no build output
here to justify them.
