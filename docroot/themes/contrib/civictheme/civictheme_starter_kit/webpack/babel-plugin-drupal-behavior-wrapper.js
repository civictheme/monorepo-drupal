// Based on Babel Plugin drupal behaviors.
// https://github.com/fourkitchens/babel-plugin-drupal-behaviors

import template from 'babel-template';
import inherits from 'babel-plugin-transform-strict-mode';

const drupalBehavior = template(`Drupal.behaviors.NAME = {attach: function (context, settings) {BODY}};`);

module.exports = function (babel) {
  const t = babel.types;

  return {
    inherits,
    visitor: {
      Program: {
        exit(path) {
          if (!this.drupalBehavior) {
            this.drupalBehavior = true;

            // Relies on every component JS file having a unique name.
            // According to the naming
            // Note: difference to civictheme version of this plugin
            // we change out the behavior name to match child theme
            // machine name.
            const identifier = `civictheme_starter_kit_${this.filename.split('/').reverse()[0].replace('.js', '').replace('-', '_')}`;
            const addBehavior = drupalBehavior({
              NAME: t.identifier(identifier),
              BODY: path.node.body,
            });

            path.replaceWith(t.program([addBehavior]));
          }
          path.node.directives = [];
        },
      },
    },
  };
};
