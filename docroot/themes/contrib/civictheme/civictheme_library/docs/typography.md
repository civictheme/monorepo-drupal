# Typography

CivicTheme component library provides a system for easily consuming and extending
typography mixins and variables.

## Fonts

The default font-families are defined in a map `$ct-fonts-default`:

```scss
$ct-fonts-default: (
  'primary': (
    'family': '"Lexend", sans-serif',
    'types': (
      (
        'uri': 'https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&display=swap',
      ),
    ),
  ),
  // ...
);
```

This map can be extended using `$ct-fonts` map (fonts can be stored with
the library or linked to a remote location):

```scss
$ct-fonts: (
  'tertiary': (
    'family': 'Roboto, sans-serif',
    'types': (
      (
        'uri': (
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Regular.ttf',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Regular.woff',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Regular.eot',
        ),
      ),
      (
        'italic': true,
        'uri': (
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Italic.ttf',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Italic.woff',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Italic.eot',
        ),
      ),
      (
        'weight': 'bold',
        'uri': (
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Bold.ttf',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Bold.woff',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Bold.eot',
        ),
      ),
      (
        'italic': true,
        'weight': 'bold',
        'uri': (
          '#{$ct-assets-directory}fonts/Roboto/Roboto-BoldItalic.ttf',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-BoldItalic.woff',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-BoldItalic.eot',
        ),
      ),
      (
        'weight': 300,
        'uri': (
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Thin.ttf',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Thin.woff',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Thin.eot',
        ),
      ),
      (
        'weight': 700,
        'uri': (
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Black.ttf',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Black.woff',
          '#{$ct-assets-directory}fonts/Roboto/Roboto-Black.eot',
        ),
      ),
    ),
  ),
  // ...
);
```

### Defining typography

The default typography is set in a map `$ct-typography-default`:

```scss
$ct-typography-default: (
  // Headings.
  'heading-1': (
    'xxs': ($ct-font-base-size * 2, $ct-font-base-line-height * 2.5, 700, 'primary', -0.6px),
    'm': ($ct-font-base-size * 3, $ct-font-base-line-height * 3.75, 700, 'primary', -1px)
  ),
  // ...
);
```

This can be extended using `$ct-typography` map:

```scss
$ct-typography: (
  'body-extra-large': (
    'xxs': ($ct-font-base-size * 2, $ct-font-base-line-height * 2.5, 700, 'primary', -0.6px),
    'm': ($ct-font-base-size * 3, $ct-font-base-line-height * 3.75, 700, 'primary', -1px)
  ),
);
```

## Using typography

Typography can be set for elements using the `ct-typography()` mixin
with a pre-defined mapping:

```scss
h1 {
  @include ct-typography('heading-l');
}
```
