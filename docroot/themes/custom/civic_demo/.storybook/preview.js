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
  options: {
    storySort: {
      order: ['Base', ['*', 'Collapsible', 'Responsive'], '*'],
    },
  },
  backgrounds: {
    default: 'White',
    values: [
      {
        name: 'White',
        value: '#fff',
      },
      {
        name: 'Light',
        value: '#f2f4f5',
      },
      {
        name: 'Dark',
        value: '#002a39',
      },
    ],
  },
  viewport: {
    viewports: {
      xs: {
        name: 'XS',
        styles: {
          width: '368px',
          height: '568px',
        },
        type: 'mobile'
      },
      s: {
        name: 'S',
        styles: {
          width: '576px',
          height: '896px',
        },
        type: 'mobile'
      },
      m: {
        name: 'M',
        styles: {
          width: '768px',
          height: '1112px',
        },
        type: 'tablet'
      },
      l: {
        name: 'L',
        styles: {
          width: '992px',
          height: '1112px',
        },
        type: 'desktop'
      },
      xl: {
        name: 'XL',
        styles: {
          width: '1280px',
          height: '1024px',
        },
        type: 'desktop'
      },
      xxl: {
        name: 'XXL',
        styles: {
          width: '1440px',
          height: '900px',
        },
        type: 'desktop'
      },
    },
  },
};
