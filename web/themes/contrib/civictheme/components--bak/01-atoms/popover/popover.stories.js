// phpcs:ignoreFile
/**
 * CivicTheme Popover component stories.
 */

import Component from './popover.twig';

const meta = {
  title: 'Atoms/Popover',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    trigger: {
      control: { type: 'object' },
    },
    content: {
      control: { type: 'text' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    group: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Popover = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    trigger: { text: 'Click me', url: '', is_new_window: false },
    content: 'Popover content',
    content_top: '',
    content_bottom: '',
    group: 'group-name',
    modifier_class: '',
    attributes: null,
  },
};
