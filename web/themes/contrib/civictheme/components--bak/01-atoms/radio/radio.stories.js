// phpcs:ignoreFile
/**
 * CivicTheme Radio component stories.
 */

import Component from './radio.twig';

const meta = {
  title: 'Atoms/Form Controls/Radio',
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

export const Radio = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    label: 'Radio label',
    name: 'radio-name',
    id: 'radio-id',
    value: '123',
    is_checked: false,
    is_required: false,
    is_invalid: false,
    is_disabled: false,
    attributes: null,
    modifier_class: '',
  },
};
