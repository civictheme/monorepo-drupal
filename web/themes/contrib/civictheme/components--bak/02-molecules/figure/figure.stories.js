// phpcs:ignoreFile
/**
 * CivicTheme Figure component stories.
 */

import Component from './figure.twig';

const meta = {
  title: 'Molecules/Figure',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    url: {
      control: { type: 'text' },
    },
    alt: {
      control: { type: 'text' },
    },
    width: {
      control: { type: 'text' },
    },
    height: {
      control: { type: 'text' },
    },
    caption: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Figure = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    url: './demo/images/demo1.jpg',
    alt: 'Image alt text',
    width: '600',
    height: '',
    caption: 'Figure image caption.',
    modifier_class: '',
    attributes: null,
  },
};
