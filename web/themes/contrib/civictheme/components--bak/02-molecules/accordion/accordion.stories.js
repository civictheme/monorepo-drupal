// phpcs:ignoreFile
/**
 * CivicTheme Accordion component stories.
 */

import Component from './accordion.twig';

const meta = {
  title: 'Molecules/Accordion',
  component: Component,
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
    expand_all: {
      control: { type: 'boolean' },
    },
    with_background: {
      control: { type: 'boolean' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    modifier_class: {
      control: { type: 'text' },
    },
    panels: {
      control: { type: 'object' },
    },
  },
};

export default meta;

export const Accordion = {
  parameters: {
    layout: 'padded',
  },
  args: {
    theme: 'light',
    expand_all: false,
    with_background: false,
    vertical_spacing: 'none',
    modifier_class: '',
    attributes: null,
    panels: [
      {
        title: 'Accordion title 1',
        content: 'Accordion content 1 <a href="https://example.com">Example link</a>',
        expanded: false,
      },
      {
        title: 'Accordion title 2',
        content: 'Accordion content 2 <a href="https://example.com">Example link</a>',
        expanded: false,
      },
      {
        title: 'Accordion title 3',
        content: 'Accordion content 3 <a href="https://example.com">Example link</a>',
        expanded: false,
      },
    ],
    content_top: '',
    content_bottom: '',
  },
};
