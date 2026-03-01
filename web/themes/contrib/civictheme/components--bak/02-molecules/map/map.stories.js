// phpcs:ignoreFile
/**
 * CivicTheme Map component stories.
 */

import Component from './map.twig';

const meta = {
  title: 'Molecules/Map',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    address: {
      control: { type: 'text' },
    },
    url: {
      control: { type: 'text' },
    },
    view_url: {
      control: { type: 'text' },
    },
    view_text: {
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

export const Map = {
  parameters: {
    layout: 'padded',
  },
  args: {
    theme: 'light',
    url: 'https://maps.google.com/maps?q=australia&t=&z=3&ie=UTF8&iwloc=&output=embed',
    address: 'Australia',
    view_url: 'https://example.com',
    view_text: '',
    vertical_spacing: 'none',
    with_background: false,
    content_top: '',
    content_bottom: '',
    modifier_class: '',
    attributes: null,
  },
};
