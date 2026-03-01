// phpcs:ignoreFile
/**
 * CivicTheme Link component stories.
 */

import Component from './link.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Atoms/Link',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    text: {
      control: { type: 'text' },
    },
    title: {
      control: { type: 'text' },
    },
    hidden_text: {
      control: { type: 'text' },
    },
    url: {
      control: { type: 'text' },
    },
    is_external: {
      control: { type: 'boolean' },
    },
    is_active: {
      control: { type: 'boolean' },
    },
    is_disabled: {
      control: { type: 'boolean' },
    },
    is_new_window: {
      control: { type: 'boolean' },
    },
    icon_placement: {
      control: { type: 'radio' },
      options: ['before', 'after'],
    },
    icon_group_disabled: {
      control: { type: 'boolean' },
    },
    icon_single_only: {
      control: { type: 'boolean' },
    },
    icon: {
      control: { type: 'select' },
      options: Constants.ICONS,
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Link = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    text: 'Link text',
    title: 'Link title',
    url: 'https://www.example.com',
    is_external: false,
    is_active: false,
    is_disabled: false,
    is_new_window: false,
    icon: '',
    icon_placement: 'before',
    icon_group_disabled: false,
    icon_single_only: false,
    modifier_class: '',
    attributes: null,
  },
};
