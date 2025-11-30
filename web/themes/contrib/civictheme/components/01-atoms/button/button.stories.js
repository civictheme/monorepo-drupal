import Component from './button.twig';
import Constants from '../../../dist/constants.json'; // eslint-disable-line import/no-unresolved

const meta = {
  title: 'Atoms/Form Controls/Button',
  component: Component,
  parameters: {
    layout: 'centered',
  },
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    text: {
      control: { type: 'text' },
    },
    type: {
      control: { type: 'radio' },
      options: ['primary', 'secondary', 'tertiary'],
    },
    kind: {
      control: { type: 'radio' },
      options: ['button', 'link', 'reset', 'submit'],
    },
    size: {
      control: { type: 'radio' },
      options: ['large', 'regular', 'small'],
    },
    icon: {
      control: { type: 'select' },
      options: Constants.ICONS,
    },
    icon_placement: {
      control: { type: 'radio' },
      options: ['before', 'after'],
    },
    icon_group_disabled: {
      control: { type: 'boolean' },
    },
    icon_single_only: {
      control: { type: 'boolean' },
    },
    url: {
      control: { type: 'text' },
    },
    is_new_window: {
      control: { type: 'boolean' },
    },
    is_disabled: {
      control: { type: 'boolean' },
    },
    is_external: {
      control: { type: 'boolean' },
    },
    allow_html: {
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

export const Button = {
  args: {
    theme: 'light',
    type: 'primary',
    kind: 'button',
    size: 'regular',
    icon: '',
    icon_placement: 'after',
    icon_group_disabled: false,
    icon_single_only: false,
    text: 'My title',
    url: '',
    allow_html: false,
    is_new_window: false,
    is_external: false,
    is_disabled: false,
    attributes: '',
    modifier_class: '',
  },
};
