// phpcs:ignoreFile
/**
 * CivicTheme Next Step component stories.
 */

import Component from './next-step.twig';

const meta = {
  title: 'Molecules/Next Steps',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    content: {
      control: { type: 'text' },
    },
    link: {
      control: { type: 'object' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const NextSteps = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    title: 'Next steps title from knob',
    content: 'Short summary explaining why this link is relevant.',
    link: {
      text: 'Sign up',
      url: 'https://example.com',
      is_new_window: true,
      is_external: true,
    },
    content_top: '',
    content_bottom: '',
    modifier_class: '',
    attributes: null,
  },
};
