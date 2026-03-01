// phpcs:ignoreFile
/**
 * CivicTheme Textfield component stories.
 */

import Component from './textfield.twig';

const meta = {
  title: 'Atoms/Form Controls/Textfield',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    placeholder: {
      control: { type: 'text' },
    },
    value: {
      control: { type: 'text' },
    },
    name: {
      control: { type: 'text' },
    },
    id: {
      control: { type: 'text' },
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

export const Textfield = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    placeholder: 'Placeholder',
    value: '',
    name: 'textarea-name',
    id: 'textarea-id',
    is_required: false,
    is_invalid: false,
    is_disabled: false,
    attributes: null,
    modifier_class: '',
  },
};
