// phpcs:ignoreFile
/**
 * CivicTheme Publication Card component stories.
 */

import Component from './publication-card.twig';

const meta = {
  title: 'Molecules/List/Publication Card',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    summary: {
      control: { type: 'text' },
    },
    image: {
      control: { type: 'object' },
    },
    image_over: {
      control: { type: 'text' },
    },
    file: {
      control: { type: 'object' },
    },
    is_title_click: {
      control: { type: 'boolean' },
    },
    filename_prefix: {
      control: { type: 'text' },
    },
    content_top: {
      control: { type: 'text' },
    },
    content_middle: {
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

export const PublicationCard = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    title: 'Publication card title',
    summary: 'Summary of the publication card',
    image: {
      url: './demo/images/demo1.jpg',
      alt: 'Image alt text',
    },
    file: {
      name: 'Document.doc',
      ext: 'doc',
      url: 'https://example.com/file.pdf',
      size: '42.88 KB',
      created: '2022-01-01',
      changed: '2022-01-02',
      icon: 'word-file',
    },
    is_title_click: false,
    filename_prefix: 'File details:',
    image_over: '',
    content_top: '',
    content_middle: '',
    content_bottom: '',
    attributes: null,
    modifier_class: '',
  },
};
