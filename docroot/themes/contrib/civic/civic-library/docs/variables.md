_[Civic Documentation](../../README.md) &#8594; [Civic Component library](introduction.md) &#8594; Variables_
# Variables.

Civic Component library contains a large number of variables that modify all areas of the theme.

Variables allow for modification of:
- Theme and component specific colors
- Typography including line-height, font-size, font-weight and what fonts used
- Spacing
- Grid system

Variables set within the Civic Component Library has a corresponding variable with the `!default` flag.
This allows consumer themes to override any the variable's color without needing to change Civic Component Library SASS.

Copy and paste variables as needed into your child theme, modify their values, and remove the !default flag.
If a variable has already been assigned in your child theme, then it won’t be re-assigned by the default values in Civic Component Library.

## Where are the variables located

Variables are split into 2 files:
- `_variables.base.scss` - base variables that are used to calculate other
  variables' values.
- `_variables.components.scss` - variables that control the look of components.

These are split into 2 files to allow changing base variables without the
need to provide component variables in custom themes (e.g., to override
primary color in child theme and have it propagate to components).
