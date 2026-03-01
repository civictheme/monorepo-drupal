// phpcs:ignoreFile
/**
 * CivicTheme Video component stories.
 */

import Component from './video.twig';

const meta = {
  title: 'Atoms/Video',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    sources: {
      control: { type: 'array' },
    },
    has_controls: {
      control: { type: 'boolean' },
    },
    poster: {
      control: { type: 'text' },
    },
    width: {
      control: { type: 'text' },
    },
    height: {
      control: { type: 'text' },
    },
    fallback_text: {
      control: { type: 'text' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Video = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    has_controls: true,
    poster: 'demo/videos/demo_poster.png',
    sources: [
      {
        url: 'demo/videos/demo.webm',
        type: 'video/webm',
      },
      {
        url: 'demo/videos/demo.mp4',
        type: 'video/mp4',
      },
      {
        url: 'demo/videos/demo.avi',
        type: 'video/avi',
      },
    ],
    width: '',
    height: '',
    fallback_text: 'Your browser doesn\'t support HTML5 video tag.',
    attributes: null,
    modifier_class: '',
  },
};
