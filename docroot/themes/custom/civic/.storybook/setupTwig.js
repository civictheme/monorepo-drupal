const { resolve } = require('path');
const twigDrupal = require('twig-drupal-filters');

module.exports.namespaces = {
  atoms: resolve(__dirname, '../', 'components/01-atoms'),
  molecules: resolve(__dirname, '../', 'components/02-molecules'),
  organisms: resolve(__dirname, '../', 'components/03-organisms'),
  templates: resolve(__dirname, '../', 'components/04-templates'),
};

/**
 * Configures and extends a standard twig object.
 *
 * @param {Twig} twig - twig object that should be configured and extended.
 *
 * @returns {Twig} configured twig object.
 */
module.exports.setupTwig = function setupTwig(twig) {
  twig.cache();
  twigDrupal(twig);
  return twig;
};
