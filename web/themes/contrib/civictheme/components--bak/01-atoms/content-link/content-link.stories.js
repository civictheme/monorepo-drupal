// phpcs:ignoreFile
/**
 * CivicTheme Content Link component stories.
 */

import Component from './content-link.twig';

const meta = {
  title: 'Atoms/Content Link',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    text: {
      control: { type: 'text' },
    },
    url: {
      control: { type: 'text' },
    },
    title: {
      control: { type: 'text' },
    },
    is_new_window: {
      control: { type: 'boolean' },
    },
    is_external: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const ContentLink = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    text: 'Link text',
    title: 'Link title',
    url: 'https://example.com',
    is_new_window: false,
    is_external: false,
  },
};
