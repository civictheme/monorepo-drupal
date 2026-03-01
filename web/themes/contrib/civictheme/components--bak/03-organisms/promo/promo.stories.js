// phpcs:ignoreFile
/**
 * CivicTheme Promo component stories.
 */

import Component from './promo.twig';

const meta = {
  title: 'Organisms/Promo',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    is_contained: {
      control: { type: 'boolean' },
    },
    link: {
      control: { type: 'object' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Promo = {
  parameters: {
    layout: 'fullscreen',
  },
  args: {
    theme: 'light',
    title: 'Sign up for industry news and updates from CivicTheme',
    is_contained: true,
    link: {
      text: 'Sign up',
      url: 'https://example.com',
      is_new_window: true,
      is_external: true,
    },
    with_background: false,
    vertical_spacing: 'none',
    content_top: '',
    content: 'Officia officia deserunt sint sint magna esse in ut elit aliquip nostrud laboris.',
    content_bottom: '',
    attributes: null,
    modifier_class: '',
  },
};
