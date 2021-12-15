# Spacing

Spacing (such as margin or padding) can be applied using the `civic-space`
function.

Spaces are defined in `$civic-spacing` list variable (currently 8 defined
spaces).

```scss
div {
  padding: civic-space(1) civic-space(2);
  margin-bottom: civic-space(2);
}
```
