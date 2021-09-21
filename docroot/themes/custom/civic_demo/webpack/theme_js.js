/**
 * Import current Drupal theme scripts to be discoverable by the loader.
 */

function requireAll(r) {
  r.keys().forEach(r);
}

requireAll(require.context('../../civic/assets/js/', true, /\.js$/));
requireAll(require.context('../assets/js/', true, /\.js$/));
