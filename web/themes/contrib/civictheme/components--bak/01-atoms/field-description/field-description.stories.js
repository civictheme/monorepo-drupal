// phpcs:ignoreFile
/**
 * CivicTheme Field Description component stories.
 */

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
    modifier_class: {
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
    modifier_class: '',
    attributes: null,
  },
};
