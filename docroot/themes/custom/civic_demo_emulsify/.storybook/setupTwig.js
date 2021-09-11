const { resolve } = require('path');
const twigDrupal = require('twig-drupal-filters');
const twigBEM = require('bem-twig-extension');
const twigAddAttributes = require('add-attributes-twig-extension');

module.exports.namespaces = {
  atoms: [
    resolve(__dirname, '../../civic_emulsify', 'components/01-atoms'),
    resolve(__dirname, '../', 'components/01-atoms'),
  ],
  molecules: [
    resolve(__dirname, '../../civic_emulsify', 'components/02-molecules'),
    resolve(__dirname, '../', 'components/02-molecules'),
  ],
  organisms: [
    resolve(__dirname, '../../civic_emulsify', 'components/03-organisms'),
    resolve(__dirname, '../', 'components/03-organisms'),
  ],
  templates: [
    resolve(__dirname, '../../civic_emulsify', 'components/04-templates'),
    resolve(__dirname, '../', 'components/04-templates'),
  ]
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
  twigBEM(twig);
  twigDrupal(twig);
  twigAddAttributes(twig);
  return twig;
};
