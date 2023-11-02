const twigDrupal = require('twig-drupal-filters');

/**
 * Configures and extends a standard twig object.
 */
module.exports.setupTwig = function setupTwig(twig) {
  twig.cache();
  twigDrupal(twig);
  return twig;
};
