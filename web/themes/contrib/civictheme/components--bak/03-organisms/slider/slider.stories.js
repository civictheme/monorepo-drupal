// phpcs:ignoreFile
/**
 * CivicTheme Slider component stories.
 */

import Component from './slider.twig';

import Slide from './slide.twig';

const meta = {
  title: 'Organisms/Slider',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    title: {
      control: { type: 'text' },
    },
    slides: {
      control: { type: 'text' },
    },
    previous_label: {
      control: { type: 'text' },
    },
    next_label: {
      control: { type: 'text' },
    },
    vertical_spacing: {
      control: { type: 'radio' },
      options: ['none', 'top', 'bottom', 'both'],
    },
    with_background: {
      control: { type: 'boolean' },
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

export const Slider = {
  parameters: {
    layout: 'padded',
  },
  args: {
    theme: 'light',
    title: 'Slider title',
    slides: [1, 2, 3].map((idx) => Slide({
      theme: 'light',
      image: {
        url: './demo/images/demo1.jpg',
        alt: '',
      },
      image_position: 'before',
      tags: [`Tag ${idx}`],
      date: '20 Jan 2023 11:00',
      date_iso: '',
      date_end: '21 Jan 2023 09:00',
      date_end_iso: '',
      title: `Slide ${idx}`,
      content: 'Content',
      links: [
        {
          text: `Link ${idx}`,
          url: 'https://example.com/',
          is_new_window: false,
          is_external: false,
        },
      ],
      attributes: null,
    }).trim()).join(''),
    previous_label: 'Previous',
    next_label: 'Next',
    vertical_spacing: 'none',
    with_background: false,
    content_top: '',
    content_bottom: '',
    attributes: null,
    modifier_class: '',
  },
};
