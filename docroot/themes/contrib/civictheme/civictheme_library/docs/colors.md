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
// Child theme override - use 'primary-variant3' color variant.
$civictheme-card-heading: civictheme-color('primary', 3);
```

The resulting css uses the child theme's component colour.
