// phpcs:ignoreFile
/**
 * CivicTheme Service Card component stories.
 */

import Component from './service-card.twig';

const meta = {
  title: 'Molecules/List/Service Card',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    summary: {
      control: { type: 'text' },
    },
    links: {
      control: { type: 'array' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const ServiceCard = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    title: 'Services category title across one or two lines',
    summary: 'Summary',
    links: [
      {
        text: 'Link text',
        url: 'https://example.com',
        is_new_window: false,
        is_external: false,
      },
      {
        text: 'Link title 2',
        url: 'https://example.com',
        is_new_window: false,
        is_external: false,
      },
    ],
    content_top: '',
    content_bottom: '',
    modifier_class: '',
    attributes: null,
  },
};
