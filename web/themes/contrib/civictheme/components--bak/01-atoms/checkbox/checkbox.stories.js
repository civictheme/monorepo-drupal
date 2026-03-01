// phpcs:ignoreFile
/**
 * CivicTheme Checkbox component stories.
 */

import Component from './checkbox.twig';

const meta = {
  title: 'Atoms/Form Controls/Checkbox',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    label: {
      control: { type: 'text' },
    },
    name: {
      control: { type: 'text' },
    },
    id: {
      control: { type: 'text' },
    },
    value: {
      control: { type: 'text' },
    },
    is_checked: {
      control: { type: 'boolean' },
    },
    is_required: {
      control: { type: 'boolean' },
    },
    is_invalid: {
      control: { type: 'boolean' },
    },
    is_disabled: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Checkbox = {
  title: 'Atoms/Form Controls/Checkbox',
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    label: 'This is a checkbox',
    name: 'name',
    id: 'id-name',
    value: '50',
    is_checked: false,
    is_required: false,
    is_invalid: false,
    is_disabled: false,
  },
};
