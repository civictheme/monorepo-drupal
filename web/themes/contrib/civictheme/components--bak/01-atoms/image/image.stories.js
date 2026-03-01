// phpcs:ignoreFile
/**
 * CivicTheme Image component stories.
 */

import Component from './image.twig';

const meta = {
  title: 'Atoms/Image',
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
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Image = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    url: './demo/images/demo5.jpg',
    alt: 'Alternative text',
    width: '',
    height: '',
    modifier_class: '',
    attributes: null,
  },
};
