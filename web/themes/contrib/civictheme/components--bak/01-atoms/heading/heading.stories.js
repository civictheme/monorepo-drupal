// phpcs:ignoreFile
/**
 * CivicTheme Heading component stories.
 */

import Component from './heading.twig';

const meta = {
  title: 'Atoms/Heading',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    level: {
      control: { type: 'radio' },
      options: ['1', '2', '3', '4', '5', '6'],
    },
    content: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Heading = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    level: '1',
    content: 'Heading content',
    modifier_class: '',
    attributes: null,
  },
};
