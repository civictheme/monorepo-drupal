# Colors

CivicTheme Component Library provides an expansive colour design system out of the
box providing for extensive customisation of a child theme.

## Theme colors

We use a subset of all colors to create a core color palette for generating
color schemes.
The colour system is generated programmatically based on **core** colors
provided.

Core CivicTheme colors are set `$civictheme-default-colors` located
[_variables.base.scss](../components/00-base/_variables.base.scss)

```scss
$civictheme-default-colors: (
  'primary': #00698F,
  'secondary': #9263DE,
  'accent': #61DAFF,
  // ...
);
```

Child themes can override or extend these core colors `$civictheme-colors` within
their own sass file system.

```scss
$civictheme-colors: (
  'primary': red,
  'secondary': green,
  'accent': blue,
  // ...
);
```

The modifiable colour system can be found in
[_variables.base.scss](../components/00-base/_variables.base.scss).

## Available colors

See storybook `Base/colors` for a visual guide to what available colors, shades and neutrals are ready to use

Color values are accessed with SASS maps:

### Dark shades
```sass

civictheme-color-shade-dark(0)
civictheme-color-shade-dark(10)
civictheme-color-shade-dark(20)
civictheme-color-shade-dark(30)
civictheme-color-shade-dark(40)
civictheme-color-shade-dark(50)
civictheme-color-shade-dark(60)
civictheme-color-shade-dark(70)
civictheme-color-shade-dark(80)
civictheme-color-shade-dark(90)
```

### Light shades
```sass

civictheme-color-shade-light(0)
civictheme-color-shade-light(10)
civictheme-color-shade-light(20)
civictheme-color-shade-light(30)
civictheme-color-shade-light(40)
civictheme-color-shade-light(50)
civictheme-color-shade-light(60)
civictheme-color-shade-light(70)
civictheme-color-shade-light(80)
civictheme-color-shade-light(90)
```


### Neutral shades

```sass

civictheme-color-neutral(0)
civictheme-color-neutral(10)
civictheme-color-neutral(20)
civictheme-color-neutral(30)
civictheme-color-neutral(40)
civictheme-color-neutral(50)
civictheme-color-neutral(60)
civictheme-color-neutral(70)
civictheme-color-neutral(80)
civictheme-color-neutral(90)
```

## Colour variables

Every color used within the CivicTheme Component Library has a corresponding variable
with the `!default` flag.
This allows consumer themes to override any the variable's color without needing
to change CivicTheme Component Library SASS.

Copy and paste variables as needed into your child theme, modify their values,
and remove the !default flag.
If a variable has already been assigned in your child theme, then it won’t be
re-assigned by the default values in CivicTheme Component Library.

You will find the complete list of CivicTheme Component Library’s color variables
in [_variables.components.scss](../components/00-base/_variables.components.scss).

### An example of overriding variables

#### CivicTheme theme default implementation
```scss
// CivicTheme theme implementation.

$civictheme-card-heading: civictheme-color('primary') !default;

.civictheme-card__heading {
  color: $civictheme-card-heading;
}
```
#### Child theme override

```sass
// Child theme override.
$civictheme-card-heading: civictheme-color-shade-dark(90);
```

The resulting css uses the child theme's component colour.
