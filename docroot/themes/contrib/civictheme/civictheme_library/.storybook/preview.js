// phpcs:ignoreFile
require('twig');

const storyOrder = [
  'Base',
  [
    'Colors',
    'Background',
    'Elevation',
    'Fonts',
    'Grid',
    'Icon',
    'Item Grid',
    'Item List',
    'Spacing',
    'Collapsible',
    'Responsive',
  ],
  '*',
]

export const parameters = {
  a11y: {
    element: '#root',
    config: {},
    options: {},
    manual: true,
  },
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
        value: '#fff',
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
