# Typography

CivicTheme component library provides a system for easily consuming and extending
typography mixins and variables.

## Fonts

The default font-families are defined in a map `$civictheme-fonts-default`:

```scss
$civictheme-fonts-default: (
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

This map can be extended using `$civictheme-fonts` map (fonts can be stored with
the library or linked to a remote location):

```scss
$civictheme-fonts: (
  'tertiary': (
    'family': 'Roboto, sans-serif',
    'types': (
      (
        'uri': (
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Regular.ttf',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Regular.woff',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Regular.eot',
        ),
      ),
      (
        'italic': true,
        'uri': (
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Italic.ttf',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Italic.woff',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Italic.eot',
        ),
      ),
      (
        'weight': 'bold',
        'uri': (
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Bold.ttf',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Bold.woff',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Bold.eot',
        ),
      ),
      (
        'italic': true,
        'weight': 'bold',
        'uri': (
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-BoldItalic.ttf',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-BoldItalic.woff',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-BoldItalic.eot',
        ),
      ),
      (
        'weight': 300,
        'uri': (
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Thin.ttf',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Thin.woff',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Thin.eot',
        ),
      ),
      (
        'weight': 700,
        'uri': (
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Black.ttf',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Black.woff',
          '#{$civictheme-assets-directory}fonts/Roboto/Roboto-Black.eot',
        ),
      ),
    ),
  ),
  // ...
);
```

### Defining typography

The default typography is set in a map `$civictheme-typography-default`:

```scss
$civictheme-typography-default: (
  // Headings.
  'heading-1': (
    'xxs': ($civictheme-font-base-size * 2, $civictheme-font-base-line-height * 2.5, 700, 'primary', -0.6px),
    'm': ($civictheme-font-base-size * 3, $civictheme-font-base-line-height * 3.75, 700, 'primary', -1px)
  ),
  // ...
);
```

This can be extended using `$civictheme-typography` map:

```scss
$civictheme-typography: (
  'body-extra-large': (
    'xxs': ($civictheme-font-base-size * 2, $civictheme-font-base-line-height * 2.5, 700, 'primary', -0.6px),
    'm': ($civictheme-font-base-size * 3, $civictheme-font-base-line-height * 3.75, 700, 'primary', -1px)
  ),
);
```

## Using typography

Typography can be set for elements using the `civictheme-typography()` mixin
with a pre-defined mapping:

```scss
h1 {
  @include civictheme-typography('heading-l');
}
```
