// phpcs:ignoreFile
/**
 * CivicTheme Snippet component stories.
 */

import Component from './snippet.twig';
import SnippetData from './snippet.stories.data';

const meta = {
  title: 'Molecules/List/Snippet',
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
    link: {
      control: { type: 'object' },
    },
    tags: {
      control: { type: 'array' },
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

export const Snippet = {
  parameters: {
    layout: 'centered',
  },
  args: SnippetData.args('light'),
};
