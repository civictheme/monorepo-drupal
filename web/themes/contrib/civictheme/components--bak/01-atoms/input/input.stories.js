// phpcs:ignoreFile
/**
 * CivicTheme Input component stories.
 */

import Component from './input.twig';

const meta = {
  title: 'Atoms/Form Controls/Input',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'radio' },
      options: [
        'color',
        'date',
        'email',
        'file',
        'image',
        'month',
        'number',
        'password',
        'range',
        'search',
        'tel',
        'time',
        'url',
        'week',
        'other',
      ],
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

export const Input = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    type: 'email',
    placeholder: 'Placeholder',
    value: '',
    name: 'input-name',
    id: 'input-id',
    is_required: false,
    is_invalid: false,
    is_disabled: false,
    modifier_class: '',
    attributes: null,
  },
};
