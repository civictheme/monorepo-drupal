// phpcs:ignoreFile
import Twig from 'twig';

import { addDecorator } from '@storybook/html';
import { useEffect } from '@storybook/client-api';
import { setupTwig } from './setupTwig';

// Add support for running scripts wrapped in Drupal.behaviours.
import './drupal_behaviors';
import { decoratorStoryLayout } from '../components_combined/00-base/storybook/storybook.layout.utils';
import { decoratorDocs } from '../components_combined/00-base/storybook/storybook.docs.utils';

// Call attaching of behaviours.
addDecorator((storyFn) => {
  useEffect(() => Drupal.attachBehaviors(), []);
  return storyFn();
});

// Setup twig support.
setupTwig(Twig);

const storyOrder = [
  'Welcome',
  'About CivicTheme',
  'Base',
  [
    'Colors',
    'Fonts',
    'Typography',
    'Icon',
    'Background',
    'Elevation',
    'Grid',
    'Layout',
    'Spacing',
    'Item List',
    'Utilities',
    'Storybook',
    [
      'Overview',
      '*',
    ],
  ],
  '*',
  'Atoms',
  [
    'Chip',
    'Content Link',
    'Heading',
    'Iframe',
    'Image',
    'Form Controls',
  ],
  '*',
  'Molecules',
  [
    'Accordion',
    'Attachment',
    'Back To Top',
    'Basic Content',
    'Breadcrumb',
    'Callout',
    'Field',
    'Figure',
    'List',
    [
      'Single Filter',
      'Group Filter',
      'Pagination',
      '*',
      'Snippet',
    ],
    '*',
  ],
  '*',
  'Organisms',
  '*',
  'Templates',
  '*',
];

export const parameters = {
  a11y: {
    element: '#root',
    config: {},
    options: {},
    manual: true,
  },
  actions: { argTypesRegex: '^on[A-Z].*' },
  options: {
    storySort: {
      order: storyOrder,
    },
  },
  backgrounds: {
    default: 'White',
    values: [
      {
        name: 'White',
        value: '#ffffff',
      },
      {
        name: 'Light',
        value: '#f2f4f5',
      },
      {
        name: 'Dark',
        value: '#003f56',
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
        type: 'mobile',
      },
      s: {
        name: 'S',
        styles: {
          width: '576px',
          height: '896px',
        },
        type: 'mobile',
      },
      m: {
        name: 'M',
        styles: {
          width: '768px',
          height: '1112px',
        },
        type: 'tablet',
      },
      l: {
        name: 'L',
        styles: {
          width: '992px',
          height: '1112px',
        },
        type: 'desktop',
      },
      xl: {
        name: 'XL',
        styles: {
          width: '1280px',
          height: '1024px',
        },
        type: 'desktop',
      },
      xxl: {
        name: 'XXL',
        styles: {
          width: '1440px',
          height: '900px',
        },
        type: 'desktop',
      },
    },
  },
};

export const decorators = [decoratorStoryLayout, decoratorDocs];

export const globalTypes = {
  resizer: {
    name: 'Resizer',
    description: 'Resize component wrapper',
    defaultValue: false,
    toolbar: {
      icon: 'component',
      items: [
        {
          value: true,
          title: 'Enabled',
          type: 'item',
        },
        {
          value: false,
          title: 'Disabled',
          type: 'reset',
        },
      ],
    },
  },
};
