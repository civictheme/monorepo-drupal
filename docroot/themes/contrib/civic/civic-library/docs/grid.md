# Grid

Civic provides a customisable grid utilising a flex-based system.

## Grid System

Civic Component Library's grid system uses a series of containers, rows, and
columns to layout and align content.

It’s built with flexbox and is fully responsive.

The system is adapted from the [SASS flexbox grid](http://sassflexboxgrid.com/).

### Example grid system markup

```html

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-l-6">100% extra-small viewport / 50% large viewport</div>
    </div>
    <div class="row">
        <div class="col-xs-6 col-m-4 col-l-3">50% extra-small viewport / 33% medium viewport / 25% large viewport</div>
        <div class="col-xs-6 col-m-4 col-l-3">50% extra-small viewport / 33% medium viewport / 25% large viewport</div>
        <div class="col-xs-6 col-m-4 col-l-3">50% extra-small viewport / 33% medium viewport / 25% large viewport</div>
        <div class="col-xs-6 col-m-4 col-l-3">50% extra-small viewport / 33% medium viewport / 25% large viewport</div>
        <div class="col-xs-6 col-m-4 col-l-3">50% extra-small viewport / 33% medium viewport / 25% large viewport</div>
    </div>
    <div class="row">
        <div class="col-xxs-12 ">100% at all viewports</div>
    </div>
    <div class="row">
        <div class="col-l-6">
            <div class="container">
                <div class="row">
                    <div class="col-l-12">Nested column is set within 50% width parent and so is 50% parent container width</div>
                </div>
            </div>
        </div>
    </div>
</div>


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

Breakpoints should be applied from smallest to largest, with all shared rules
defined before the breakpoints.

All breakpoints use `min-width` only. Options for `min and max`, or `max` width
has been omitted to avoid over-complicating breakpoint logic and readability.

## Containers and rows

Containers are used to contain and center content to a max-width per breakpoint.
These are set via the `$civic-breakpoints` map.

If no container is set then columns spread full-width - this is utilised in
Civic Page without sidebar where we have full-width components and constraining
of content is done on the component level.

Rows are wrappers for columns. Each column has horizontal padding (called a
gutter) for controlling the space between them. This padding is then
counteracted on the rows with negative margins to ensure the content in your
columns is visually aligned down the left side.

## Columns

There are 12 columns available per row, allowing you to create different
combinations of elements that span any number of columns.

Column classes indicate the number of template columns to span (e.g., col-4
spans four). Widths are set in percentages so you always have the same relative
sizing.

## Nested Grids

The grid system allows for nested grids. If a parent is 50% width (`col-xs-12`)
and a container, row and column is placed inside of this column then the
max-width of the child column (with `col-xs-12` class) is 50% of the parent.

## Utilities

Civic component library come with a variety of
[Grid utility mixins](../components/00-base/mixins/_grid.scss) including
`offset`, `row-reverse`, `flex-column` among others.

## Advanced

For more advanced modification and overriding, the grid system internals are
defined within `_variables.base.scss`, which provides plenty of options to
change the grid system however this is not recommended as it can have
significant changes to the design system which may not be at first apparent.
