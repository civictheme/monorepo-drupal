// phpcs:ignoreFile
/**
 * CivicTheme Message component stories.
 */

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
    title: {
      control: { type: 'text' },
    },
    content: {
      control: { type: 'text' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
    },
    has_aria: {
      control: { type: 'boolean' },
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
    type: 'information',
    title: 'The information on this page is currently being updated.',
    content: 'Message description',
    vertical_spacing: 'none',
    with_background: false,
    has_aria: true,
    attributes: null,
    modifier_class: '',
  },
};
