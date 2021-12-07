# Element sizing

All element sizes should be defined in `px` but converted to `rem` through
the `rem()` mixin. This provides a clear size coversion from the designs, while
allowing for whole-page scalability. The only exception where `px` should be
used is on single pixel borders.

```scss
div {
  max-width: rem(450px);
}
```
