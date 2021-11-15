function requireAll(r) {
  r.keys().forEach(r);
}
requireAll(require.context('../assets/fonts/', true, /\.(woff|woff2|ttf|eot)$/));
