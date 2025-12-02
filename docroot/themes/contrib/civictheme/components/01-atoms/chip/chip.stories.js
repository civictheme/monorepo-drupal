import Component from './chip.twig';
import './chip.event.stories';

const meta = {
  title: 'Atoms/Chip',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    kind: {
      control: { type: 'radio' },
      options: {
        Default: 'default',
        Input: 'input',
      },
    },
    size: {
      control: { type: 'radio' },
      options: {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        None: '',
      },
    },
    content: {
      control: { type: 'text' },
    },
    is_selected: {
      control: { type: 'boolean' },
    },
    is_multiple: {
      control: { type: 'boolean' },
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

export const Chip = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    kind: 'default',
    size: 'regular',
    content: 'Chip label',
    is_selected: false,
    is_multiple: false,
  },
};
