/**
 * Import SVG files to be discoverable by the loader.
 */

function requireAll(r) {
  r.keys().forEach(r);
}

requireAll(require.context('../civic-library/assets/icons/', true, /\.svg$/));
requireAll(require.context('../assets/icons/', true, /\.svg$/));
