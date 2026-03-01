// phpcs:ignoreFile
/**
 * CivicTheme Label component stories.
 */

import Component from './label.twig';

const meta = {
  title: 'Atoms/Form Controls/Label',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    tag: {
      control: { type: 'radio' },
      options: ['label', 'legend'],
    },
    content: {
      control: { type: 'text' },
    },
    size: {
      control: { type: 'select' },
      options: [
        '',
        'extra-small',
        'small',
        'regular',
        'large',
        'extra-large',
      ],
    },
    is_required: {
      control: { type: 'boolean' },
    },
    required_text: {
      control: { type: 'text' },
    },
    for: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Label = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    tag: 'label',
    content: 'Label content',
    size: 'regular',
    is_required: false,
    required_text: '',
    for: '',
    attributes: null,
    modifier_class: '',
  },
};
