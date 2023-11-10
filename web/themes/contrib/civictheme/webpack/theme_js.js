// phpcs:ignoreFile
/**
 * Import current Drupal theme scripts to be discoverable by the loader.
 */

function requireAll(r) {
  r.keys().forEach(r);
}

requireAll(require.context('../assets/js/', true, /\.js$/));
