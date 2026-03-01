// phpcs:ignoreFile
/**
 * CivicTheme Paragraph component stories.
 */

import Component from './paragraph.twig';

const meta = {
  title: 'Atoms/Paragraph',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content: {
      control: { type: 'text' },
    },
    size: {
      control: { type: 'radio' },
      options: ['extra-large', 'large', 'regular', 'small'],
    },
    no_margin: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Paragraph = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed sit amet nulla auctor, vestibulum magna sed, convallis ex.',
    size: 'regular',
    no_margin: false,
    modifier_class: '',
    attributes: null,
  },
};
