# Colors

Core civic colors live in `$civic-default-colors`.

```scss
$civic-default-colors: (
  'primary': #00698F,
  'secondary': #9263DE,
  'accent': #61DAFF,
  // ...
);
```

Subsites can override or extended these core colors by using `$civic-colors`.

```scss
$civic-colors: (
  'primary': red,
  'secondary': green,
  'accent': blue,
  // ...
);
```

Every color used on the site should have a corresponding variable, with an
appended `!default`. This ensures subsites can override any individual color by
redefinind the variable.

```scss
$civic-card-heading: civic-color('primary') !default;

.civic-card__heading {
  color: $civic-card-heading;
}
```
