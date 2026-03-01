// phpcs:ignoreFile
/**
 * CivicTheme Breadcrumb component stories.
 */

import Components from './breadcrumb.twig';

const meta = {
  title: 'Molecules/Breadcrumb',
  component: Components,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    active_is_link: {
      control: { type: 'boolean' },
    },
    links: {
      control: { type: 'object' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Breadcrumb = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    active_is_link: false,
    links: [
      {
        text: 'Link 1',
        url: 'https://example.com/link1',
      },
      {
        text: 'Link 2',
        url: 'https://example.com/link2',
      },
      {
        text: 'Link 3',
        url: 'https://example.com/link3',
      },
    ],
    modifier_class: '',
    attributes: null,
  },
};
