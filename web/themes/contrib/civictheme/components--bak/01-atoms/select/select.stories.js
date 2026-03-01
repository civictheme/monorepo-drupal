// phpcs:ignoreFile
/**
 * CivicTheme Select component stories.
 */

import Component from './select.twig';

const meta = {
  title: 'Atoms/Form Controls/Select',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    is_multiple: {
      control: { type: 'boolean' },
    },
    options: {
      control: { type: 'array' },
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

export const Select = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    is_multiple: false,
    options: [
      {
        type: 'optgroup',
        label: 'Group label',
        value: 'Group value',
        selected: false,
        options: [
          {
            label: 'Option label',
            value: 'Option value',
            is_selected: false,
            is_disabled: false,
          },
        ],
      },
      {
        type: 'option',
        label: 'Option label',
        value: 'Option value',
        selected: false,
      },
    ],
    name: 'Select name',
    id: 'select-id',
    is_required: false,
    is_invalid: false,
    is_disabled: false,
    attributes: null,
    modifier_class: '',
  },
};
