# Grid

Civic theme provides a customisable grid system.

The grid system is defined within `_variables.base.scss` which provides plenty of options that can be
overridden in the child theme.

```sass
//
// Grid.
//
$civic-grid-columns: 12 !default;

// Grid spacing used to calculate gutter spacings. Since the grid is based on
// pixel values instead of rems (for consistency), the spacing is also based on
// pixel values.
$civic-grid-space: 8px !default;

// The lowest breakpoint where column classes should start applying.
$civic-grid-lowest-breakpoint: 'xxs' !default;

// The width of the fluid container at max width. Used to contain fluid
// containers on wide screens. Set to 'auto' to keep fluid.
$civic-grid-max-width: map.get($civic-breakpoints, 'xxl') !default;

// Spacing between columns in a row.
$civic-grid-gutters: (
  'xxs': $civic-grid-space * 2,
  'xs': $civic-grid-space * 2,
  's': $civic-grid-space * 3
) !default;

// Side spacing between the edge of the viewport and a start of the grid.
$civic-grid-offsets: (
  'xxs': $civic-grid-space * 3,
  'xs': $civic-grid-space * 3,
  's': $civic-grid-space * 3,
  'm': $civic-grid-space * 3,
  'l': $civic-grid-space * 6,
  'xl': $civic-grid-space * 6,
  'xxl': $civic-grid-space * 12,
) !default;
```

For a visual demonstration the grid system can be viewed in storybook.

Changing the grid system radically can require extensive changes to the templates as they rely on 12 columns
to mean 100% width within a container.
