# Breakpoints

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

Breakpoints should be applied from smallest to largest, with all shared rules
defined before the breakpoints.

All breakpoints use `min-width` only. Options for `min and max`, or `max` width
has been omitted to avoid over-complicating breakpoint logic and readability.
