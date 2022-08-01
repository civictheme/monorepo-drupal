// phpcs:ignoreFile
function requireAll(r) {
  r.keys().forEach(r);
}
requireAll(require.context('../assets/', true, /\/(?!.*ajax-views).*\.*/));
