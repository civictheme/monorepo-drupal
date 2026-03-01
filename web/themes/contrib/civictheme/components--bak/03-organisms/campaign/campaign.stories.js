// phpcs:ignoreFile
/**
 * CivicTheme Campaign component stories.
 */

import Component from './campaign.twig';

const meta = {
  title: 'Organisms/Campaign',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    image: {
      control: { type: 'object' },
    },
    image_position: {
      control: { type: 'radio' },
      options: ['left', 'right'],
    },
    tags: {
      control: { type: 'array' },
    },
    title: {
      control: { type: 'text' },
    },
    date: {
      control: { type: 'text' },
    },
    links: {
      control: { type: 'array' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['top', 'bottom', 'both'],
    },
    content_top: {
      control: { type: 'text' },
    },
    content: {
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

export const Campaign = {
  parameters: {
    layout: 'fullscreen',
  },
  args: {
    theme: 'light',
    title: 'Campaign heading which runs across two or three lines',
    content: 'Quis non in nostrud incididunt voluptate ea enim cillum ex ut reprehenderit proident enim officia velit.',
    date: '11 Dec 2024',
    image: {
      url: './demo/images/demo4.jpg',
      alt: 'Image alt text',
    },
    image_position: 'left',
    tags: [
      'Tag 1',
    ],
    links: [
      {
        text: 'Link 1',
        url: 'https://example.com/link',
        is_new_window: false,
        is_external: false,
      },
      {
        text: 'Link 2',
        url: 'https://example.com/link-2',
        is_new_window: false,
        is_external: false,
      },
    ],
    vertical_spacing: 'both',
    content_top: '',
    content_bottom: '',
    attributes: null,
    modifier_class: '',
  },
};
