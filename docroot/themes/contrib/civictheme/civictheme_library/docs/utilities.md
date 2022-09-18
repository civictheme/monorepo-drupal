# Utilities

CivicTheme component library provides a wide range of SASS utilities out of the
box.

## Spacing

Spacing (such as margin or padding) can be applied using the `ct-space()`
function.

Spaces are defined in `$ct-spacing` list variable.

```scss
div {
  padding: ct-space(1) ct-space(2);
  margin-bottom: ct-space(2);
}
```

## Element sizing

All element sizes should be defined in `px` but converted to `rem` through
the `rem()` function.

This provides a clear size conversion from the designs, while allowing for
whole-page scalability. The only exception where `px` should be used is on
single-pixel borders.

```scss
div {
  max-width: rem(450px);
}
```
