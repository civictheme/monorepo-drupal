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
    allow_html: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
    attributes: {
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
    allow_html: true,
    modifier_class: '',
    attributes: '',
  },
};
