import Component from './field-description.twig';

const meta = {
  title: 'Atoms/Form Controls/Field Description',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    size: {
      control: { type: 'radio' },
      options: ['large', 'regular'],
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

export const FieldDescription = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    size: 'regular',
    content: 'Field message content sample.',
    allow_html: true,
    modifier_class: '',
    attributes: '',
  },
};
