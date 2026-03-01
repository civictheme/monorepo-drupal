// phpcs:ignoreFile
/**
 * CivicTheme Textarea component stories.
 */

import Component from './textarea.twig';

const meta = {
  title: 'Atoms/Form Controls/Textarea',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    rows: {
      control: { type: 'number', min: 1, max: 10, step: 1 },
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

export const Textarea = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    rows: 5,
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
