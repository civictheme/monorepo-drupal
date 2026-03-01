// phpcs:ignoreFile
/**
 * CivicTheme Webform component stories.
 */

import Component from './webform.twig';

const meta = {
  title: 'Organisms/Webform',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    referenced_webform: {
      control: { type: 'text' },
    },
    with_background: {
      control: { type: 'boolean' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Webform = {
  parameters: {
    layout: 'padded',
  },
  args: {
    theme: 'light',
    referenced_webform: 'Webform title',
    with_background: false,
    vertical_spacing: 'none',
    attributes: null,
    modifier_class: '',
  },
};
