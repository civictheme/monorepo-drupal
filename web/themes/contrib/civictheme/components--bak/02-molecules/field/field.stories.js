// phpcs:ignoreFile
/**
 * CivicTheme Field component stories.
 */

import Component from './field.twig';
import FieldData from './field.stories.data';

const meta = {
  title: 'Molecules/Field',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'select' },
      options: ['textfield', 'textarea', 'select', 'select_multiple', 'radio', 'checkbox', 'hidden', 'color', 'date', 'email', 'file', 'image', 'month', 'number', 'password', 'range', 'search', 'tel', 'time', 'url', 'week', 'other'],
    },
    title: {
      control: { type: 'text' },
    },
    title_display: {
      control: { type: 'radio' },
      options: ['visible', 'invisible', 'hidden'],
    },
    title_size: {
      control: { type: 'radio' },
      options: ['extra-small', 'small', 'regular', 'large', 'extra-large'],
    },
    description: {
      control: { type: 'text' },
    },
    name: {
      control: { type: 'text' },
    },
    value: {
      control: { type: 'text' },
    },
    placeholder: {
      control: { type: 'text' },
    },
    id: {
      control: { type: 'text' },
    },
    is_invalid: {
      control: { type: 'boolean' },
    },
    is_disabled: {
      control: { type: 'boolean' },
    },
    is_required: {
      control: { type: 'boolean' },
    },
    required_text: {
      control: { type: 'text' },
    },
    orientation: {
      control: { type: 'radio' },
      options: ['vertical', 'horizontal'],
    },
    is_inline: {
      control: { type: 'boolean' },
    },
    control: {
      control: { type: 'object' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
    prefix: {
      control: { type: 'text' },
    },
    suffix: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Field = {
  parameters: {
    layout: 'centered',
  },
  args: FieldData.args(),
};

export const FieldRadio = {
  parameters: {
    layout: 'centered',
  },
  args: {
    ...FieldData.args('light', { controls: true, is_required: true }),
    type: 'radio',
  },
};

export const FieldCheckbox = {
  parameters: {
    layout: 'centered',
  },
  args: {
    ...FieldData.args('light', { controls: true, is_required: true }),
    type: 'checkbox',
  },
};

export const FieldSelect = {
  parameters: {
    layout: 'centered',
  },
  args: {
    ...FieldData.args('light', { options: true, is_required: true }),
    type: 'select',
  },
};
