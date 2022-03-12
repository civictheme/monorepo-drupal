# Colors

Civic Component Library provides an expansive colour design system out of the
box providing for extensive customisation of a child theme.

## Theme colors

We use a subset of all colors to create a core color palette for generating
color schemes.
The colour system is generated programmatically based on **core** colors
provided.

Core Civic colors are set `$civic-default-colors` located
[_variables.base.scss](../components/00-base/_variables.base.scss)

```scss
$civic-default-colors: (
  'primary': #00698F,
  'secondary': #9263DE,
  'accent': #61DAFF,
  // ...
);
```

Child themes can override or extend these core colors `$civic-colors` within
their own sass file system.

```scss
$civic-colors: (
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

civic-color-shade-dark(0)
civic-color-shade-dark(10)
civic-color-shade-dark(20)
civic-color-shade-dark(30)
civic-color-shade-dark(40)
civic-color-shade-dark(50)
civic-color-shade-dark(60)
civic-color-shade-dark(70)
civic-color-shade-dark(80)
civic-color-shade-dark(90)
```

### Light shades
```sass

civic-color-shade-light(0)
civic-color-shade-light(10)
civic-color-shade-light(20)
civic-color-shade-light(30)
civic-color-shade-light(40)
civic-color-shade-light(50)
civic-color-shade-light(60)
civic-color-shade-light(70)
civic-color-shade-light(80)
civic-color-shade-light(90)
```


### Neutral shades

```sass

civic-color-neutral(0)
civic-color-neutral(10)
civic-color-neutral(20)
civic-color-neutral(30)
civic-color-neutral(40)
civic-color-neutral(50)
civic-color-neutral(60)
civic-color-neutral(70)
civic-color-neutral(80)
civic-color-neutral(90)
```

## Colour variables

Every color used within the Civic Component Library has a corresponding variable
with the `!default` flag.
This allows consumer themes to override any the variable's color without needing
to change Civic Component Library SASS.

Copy and paste variables as needed into your child theme, modify their values,
and remove the !default flag.
If a variable has already been assigned in your child theme, then it won’t be
re-assigned by the default values in Civic Component Library.

You will find the complete list of Civic Component Library’s color variables
in [_variables.components.scss](../components/00-base/_variables.components.scss).

### An example of overriding variables

#### Civic theme default implementation
```scss
// Civic theme implementation.

$civic-card-heading: civic-color('primary') !default;

.civic-card__heading {
  color: $civic-card-heading;
}
```
#### Child theme override

```sass
// Child theme override.
$civic-card-heading: civic-color-shade-dark(90);
```

The resulting css uses the child theme's component colour.
