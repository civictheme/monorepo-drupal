# Civic Component Library

Civic component library with Storybook integration.

## Features

- Atomic design
- Accessible
- Platform-agnostic
- Drupal and Wordpress compatible

## Installing

    npm install

## Building

    npm run build

This will build:
  - CSS and JS assets in the `dist` directory. These files can be included
    directly into your HTML page, provided that it has components implemented
    with the same markup as components in the `componets` directory.
  - Storybook assets as compiled HTML page in the `storybook-static` directory.
    These files can be served publically to show all components available in
    the library.

## Running locally

    npm run storybook

## Civic Theme Components

## Spacing

Spacing (such as margin or padding) can be applied using the `civic-space` function.

Spaces are defined in `$civic-spacing` list variable (currently 8 defined spaces).

```scss
div {
  padding: civic-space(1) civic-space(2);
  margin-bottom: civic-space(2);
}

```

## Colours

Core civic colours live in `$civic-default-colors`.

```scss
$civic-default-colors: (
  'primary': #00698F,
  'secondary': #9263DE,
  'accent': #61DAFF,
  // ...
);
```

Subsites can override or extended these core colours by using `$civic-colors`.

```scss
$civic-colors: (
  'primary': red,
  'secondary': green,
  'accent': blue,
  // ...
);
```

Every colour used on the site should have a corresponding variable, with an appended `!default`.
This ensures subsites can override any individual colour by redefinind the variable.

```scss
$civic-card-heading: civic-color('primary') !default;

.civic-card__heading {
  color: $civic-card-heading;
}
```

## Element sizing

All element sizes should be defined in `px` but converted to `rem` through the `rem()` mixin.
This provides a clear size coversion from the designs, while allowing for whole-page scalability.
The only exception where `px` should be used is on single pixel borders.

```scss
div {
  max-width: rem(450px);
}
```

## Breakpoints

Breakpoints are defined as:

```scss
$civic-breakpoints: (
  'xs': 368px,
  's': 576px,
  'm': 768px,
  'l': 992px,
  'xl': 1280px,
  'xxl': 1440px
);
```

And can be used with the `civic-breakpoint()` mixin.

```scss
div {
  // Shared rules.
  @include civic-breakpoint(s) {
    // Mobile-and-up Small rules.
  }
  @include civic-breakpoint(m) {
    // Mobile-and-up Medium rules.
  }
  @include civic-breakpoint(xl) {
    // Mobile-and-up Extra large rules.
  }
}
```

Breakpoints should be applied from smallest to largest, with all shared rules defined before the breakpoints.

All breakpoints use `min-width` only.
Options for `min and max`, or `max` width has been omitted to avoid over-complicating breakpoint logic and readability.

## Fonts

Font and typography can be set using the `civic-typography()` mixin.

It can be used in 3 ways:

1. Passing a ruleset

```scss
h1 {
  @include civic-typography((24px, 1.2em, 400, 'primary'));
}
```

Where:

* Parameter 1 = font-size
* Parameter 2 = line-height
* Parameter 3 = font-weight
* Parameter 4 = font-family

Note:
* font-weight and font-family are optional.
* font-size in pixels will automatically be converted to rem.

The font-family is defined in a map:

```scss
$civic-font: (
  'primary': $civic-font-primary
);
```

2. Passing a ruleset for multiple breakpoints

Given the defined breakpoints (see breakpoints section):

The breakpoint labels can be used to define the typgraphy to show at that breakpoint.

```scss
h1 {
  @include typography((
    'xs': (16px, 1.5em, 400),
    'l': (24px, 1.2em, 600)
  ));
}
```

3. Presets

Given the typography presets e.g:

```scss
$civic-typography: (
  'display-xl': (56px, 60px, 700, 'primary')
);
```

A font can be used by passing a preset name:

```scss
h1 {
  @include typography('heading-l');
}
```

## Focus

Civic provides a mixin `civic_outline()` that can be used to apply a focus outline.

```scss
.button {
  &:focus {
    @include civic_outline();
  }
}
```

## Grid

Section to come.

## Component stylesheet anatomy

Component stylesheets are broken into 4 sections:

1. Imports - Where required stylesheet imports are added
2. Variables - Where component specific variables are defined
3. Mixins - Where mixins for all component styles are provided.
4. Selectors - Where the mixins are connected to selectors.

Breaking the styles into distinct `mixins` and `selectors` sections allows for parts of components to be reused without duplicating the rules.

```scss
//
// Button.
//

//
// Imports.
//

@import '../global/mixins';
@import '../global/variables';

//
// Variables.
//

$civic-button-background-color-default: civic-color('shade_60') !default;
$civic-button-background-color-primary: civic-color('primary') !default;

//
// Mixins.
//

@mixin civic-button-base () {
  background-color: $civic-button-background-color-default;
}

@mixin civic-button-primary () {
  background-color: $civic-button-background-color-primary;
}

//
// Selectors.
//

.civic-button {
  @include civic-button-base;

  &.primary {
    @include civic-button-primary;
  }
}
```
