import Twig from 'twig';
import { setupTwig } from './setupTwig';

import { addDecorator } from '@storybook/html';
import { useEffect } from '@storybook/client-api';

// Add support for running scripts wrapped in Drupal.behaviours.
import './drupal_behaviors.js';

// Call attaching of behaviours.
addDecorator((storyFn) => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return storyFn();
});

// Setup twig support.
setupTwig(Twig);

export const parameters = {
  actions: { argTypesRegex: '^on[A-Z].*' },
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
