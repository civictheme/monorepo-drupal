// phpcs:ignoreFile
/**
 * CivicTheme Field Message component stories.
 */

import Component from './field-message.twig';

const meta = {
  title: 'Atoms/Form Controls/Field Message',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'radio' },
      options: ['error', 'information', 'warning', 'success'],
    },
    content: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const FieldMessage = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    type: 'error',
    content: 'Field message content sample.',
    modifier_class: '',
    attributes: null,
  },
};
