/**
 * Import current Drupal theme SVG files to be discoverable by the loader.
 */

function requireAll(r) {
  r.keys().forEach(r);
}

requireAll(require.context('../../civic/assets/icons/', true, /\.svg$/));
requireAll(require.context('../assets/icons/', true, /\.svg$/));
