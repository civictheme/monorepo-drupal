import Component from './message.twig';

const meta = {
  title: 'Organisms/Message',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    type: {
      control: { type: 'select' },
      options: ['information', 'error', 'warning', 'success'],
    },
    description: {
      control: { type: 'text' },
    },
    attributes: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Message = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    title: 'The information on this page is currently being updated.',
    type: 'information',
    description: 'Message description',
    attributes: '',
    modifier_class: '',
  },
};
