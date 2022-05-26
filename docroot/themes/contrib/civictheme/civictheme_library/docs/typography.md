# Typography

CivicTheme Component Library provides a system for easily consuming and extending
typography mixins and variables.

### How to use the typography mixin

Font and typography can be set using the `civictheme-typography()` mixin.

It can be used in 3 ways:

#### 1. Passing a ruleset

```scss
h1 {
  @include civictheme-typography((24px, 1.2em, 400, 'primary'));
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
$civictheme-font: (
  'primary': $civictheme-font-primary
) !default;
```

#### 2. Passing a ruleset for multiple breakpoints

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

#### 3. Presets

Given the typography presets e.g:

```scss
$civictheme-typography: (
  'display-xl': (56px, 60px, 700, 'primary')
);
```

A font can be used by passing a preset name:

```scss
h1 {
  @include typography('heading-l');
}
```

### Including custom fonts

CivicTheme component library allows consumer themes to modify and extend the list of
fonts that are used.

Add custom fonts to $civictheme-fonts with the same key as above to override
fonts or with a new key to add a new font kind.
'uri' can be a URL to the external font or a list of local files with
extensions to be imported. The font type will be taken from the file extensions.
Use $civictheme-assets-directory to provide a relative path.

IMPORTANT: Make sure to put commas at the end of every list or SASS will not
be able to distinguish lists and values.

#### Example custom font.
```sass
$civictheme-fonts: (
  'tertiary': (
    'family': 'Roboto',
    'types': (
      (
        'uri': (
          '#{$civictheme-assets-directory}fonts/roboto/Roboto-Regular.ttf',
          '#{$civictheme-assets-directory}fonts/roboto/Roboto-Regular.woff',
          '#{$civictheme-assets-directory}fonts/roboto/Roboto-Regular.eot',
        ),
      ),
      (
        'italic': true,
        'weight': 'bold',
        'uri': (
          '#{$civictheme-assets-directory}fonts/roboto/Roboto-BoldItalic.ttf',
          '#{$civictheme-assets-directory}fonts/roboto/Roboto-BoldItalic.woff',
          '#{$civictheme-assets-directory}fonts/roboto/Roboto-BoldItalic.eot',
        ),
      ),
    ),
  ),
);
```
