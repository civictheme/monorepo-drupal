// phpcs:ignoreFile
/**
 * CivicTheme Callout component stories.
 */

import Components from './callout.twig';

const meta = {
  title: 'Molecules/Callout',
  component: Components,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    content_top: {
      control: { type: 'text' },
    },
    content_bottom: {
      control: { type: 'text' },
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
    links: {
      control: { type: 'array' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Callout = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    links: [
      {
        text: 'Link 1',
        url: 'https://example.com/link1',
        is_new_window: false,
        is_external: false,
      },
      {
        text: 'Link 2',
        url: 'https://example.com/link2',
        is_new_window: true,
        is_external: true,
      },
    ],
    content_top: '',
    content_bottom: '',
    title: 'Callout title from knob',
    content: 'Example content ut fugiat ex nulla enim ipsum proident aliqua in elit irure tempor elit nisi nisi enim labore nostrud mollit ut magna commodo',
    vertical_spacing: 'none',
    modifier_class: '',
    attributes: null,
  },
};
