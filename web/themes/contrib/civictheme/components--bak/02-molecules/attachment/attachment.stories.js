// phpcs:ignoreFile
/**
 * CivicTheme Attachment component stories.
 */

import Component from './attachment.twig';

const meta = {
  title: 'Molecules/Attachment',
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
    files: {
      control: { type: 'object' },
    },
    with_background: {
      control: { type: 'boolean' },
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

export const Attachment = {
  parameters: {
    layout: 'padded',
  },
  args: {
    theme: 'light',
    title: 'Attachments',
    content: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    files: [
      {
        url: 'https://example.com/document.doc',
        name: 'Document.doc',
        ext: 'doc',
        size: '42.88 KB',
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'word-file',
      },
      {
        url: 'https://example.com/document.docx',
        name: 'Document.docx',
        ext: 'docx',
        size: '32.48 KB',
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'word-file',
      },
      {
        url: 'https://example.com/document.pdf',
        name: 'Document.pdf',
        ext: 'pdf',
        size: '42.82 KB',
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'pdf-file',
      },
      {
        url: 'https://example.com/document.ppt',
        name: 'Document.ppt',
        ext: 'ppt',
        size: '12.88 KB',
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'download-file',
      },
      {
        url: 'https://example.com/document.xlsx',
        name: 'Document.xlsx',
        ext: 'xlsx',
        size: '34.45 KB',
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'download-file',
      },
      {
        url: 'https://example.com/document.xls',
        name: 'Document.xls',
        ext: 'xls',
        size: '65.67 KB',
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'download-file',
      },
      {
        url: 'https://example.com/document',
        name: 'Document',
        ext: null,
        size: null,
        created: '2022-01-01',
        changed: '2022-01-01',
        icon: 'download-file',
      },
    ],
    with_background: false,
    vertical_spacing: 'none',
    content_top: '',
    content_bottom: '',
    modifier_class: '',
    attributes: null,
  },
};
