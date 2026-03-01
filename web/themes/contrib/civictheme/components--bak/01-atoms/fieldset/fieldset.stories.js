// phpcs:ignoreFile
/**
 * CivicTheme Fieldset component stories.
 */

import Component from './fieldset.twig';
import FieldData from '../../02-molecules/field/field.stories.data';
import Field from '../../02-molecules/field/field.twig';

const meta = {
  title: 'Atoms/Form Controls/Fieldset',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    legend: {
      control: { type: 'text' },
    },
    description: {
      control: { type: 'text' },
    },
    description_display: {
      control: { type: 'radio' },
      options: ['before', 'after', 'invisible'],
    },
    message: {
      control: { type: 'text' },
    },
    message_type: {
      control: { type: 'radio' },
      options: ['error', 'information', 'warning', 'success'],
    },
    fields: {
      control: { type: 'text' },
    },
    is_required: {
      control: { type: 'boolean' },
    },
    required_text: {
      control: { type: 'text' },
    },
    prefix: {
      control: { type: 'text' },
    },
    suffix: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Fieldset = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    legend: 'Fieldset legend',
    description: 'Fieldset example description',
    description_display: 'before',
    message: 'Fieldset example message',
    message_type: 'error',
    fields: [
      Field({ ...FieldData.args('light', { controls: true, is_required: true }), type: 'checkbox', description: '', message: '' }),
      Field({ ...FieldData.args('light', { controls: true, is_required: true }), type: 'radio', message: '' }),
    ].join('').trim(),
    is_required: true,
    required_text: '',
    prefix: '',
    suffix: '',
    modifier_class: '',
    attributes: null,
  },
};
