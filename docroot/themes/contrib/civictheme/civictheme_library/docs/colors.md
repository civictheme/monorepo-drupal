# Colors

CivicTheme component library provides a robust and flexible colour design system
out of the box allowing extensive customisation.

Colour system has a multi-level mapping approach:

1. Standard colours
2. Colour variants based on derivatives of the standard colours with overridable
   defaults.
3. Components' colours are mapped to colour variants.

This allows to update the palette by customizing as little as standard colours
(level 1), or additionally customizing color variants (level 2), or customizing
per-component colours (level 3).

## Colours and variants

CivicTheme provides default colours and colour variants that can be _extended_
and/or _replaced_ as needed.

CivicTheme default colours are set in `$civictheme-default-colors` located in
[_variables.base.scss](../components/00-base/_variables.base.scss)

```scss
$civictheme-default-colors: (
  'primary': #00698F,
  'secondary': #61DAFF,
  // ...
);
```

These colours can be overridden or extended via `$civictheme-colors` variable:

```scss
$civictheme-colors: (
  'primary': red,
  'secondary': green,
  // ...
);
```

CivicTheme default colourvariants are set in `$civictheme-colors-variants-default`
located in [_variables.base.scss](../components/00-base/_variables.base.scss).
Note that colour variants have defaults set to tints, shades and tones of the
standard colours.

```scss
// Default CivicTheme colors palette.
$civictheme-colors-variants-default: (
  'primary-variant1': civictheme-color-tint(90, civictheme-color('primary')),
  'primary-variant2': civictheme-color-shade(15, civictheme-color('primary')),
  // ...
);
```

These colour variants can be overridden or extended via `$civictheme-colors-variants`
variable:

```scss
$civictheme-colors-variants: (
  // Override palette colors variant.
  'primary-variant1': lime,
  // Define custom colors variant.
  'primary-variant6': brown,
);
```

Standard colours and colour variants are mapped to component element colours:

```scss
// Using a third primary variant colour for Card heading color.
$civictheme-promo-card-light-heading-color: civictheme-color('primary', 3) !default;

.civictheme-card__heading {
  color: $civictheme-promo-card-light-heading-color;
}
```
