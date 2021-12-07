# Fonts

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

The breakpoint labels can be used to define the typgraphy to show at that
breakpoint.

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
