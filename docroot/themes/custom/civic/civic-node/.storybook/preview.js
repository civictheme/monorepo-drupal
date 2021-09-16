var Twig = require('twig')
var twigDrupal = require('twig-drupal-filters')

// Add the filters to Drupal.
twigDrupal(Twig);

export const parameters = {
  backgrounds: {
    default: 'White',
    values: [
      {
        name: 'White',
        value: '#FFFFFF',
      },
      {
        name: 'Light',
        value: '#F2F4F5',
      },
      {
        name: 'Dark',
        value: '#002A39',
      },
    ],
  },
};
