// phpcs:ignoreFile
/**
 * CivicTheme Event Card component stories.
 */

import Component from './event-card.twig';
import EventCardData from './event-card.stories.data';

const meta = {
  title: 'Molecules/List/Event Card',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    date: {
      control: { type: 'text' },
    },
    date_end: {
      control: { type: 'text' },
    },
    title: {
      control: { type: 'text' },
    },
    location: {
      control: { type: 'text' },
    },
    summary: {
      control: { type: 'text' },
    },
    link: {
      control: { type: 'object' },
    },
    is_title_click: {
      control: { type: 'boolean' },
    },
    image: {
      control: { type: 'object' },
    },
    tags: {
      control: { type: 'array' },
    },
    content_top: {
      control: { type: 'text' },
    },
    image_over: {
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

export const EventCard = {
  parameters: {
    layout: 'centered',
  },
  args: EventCardData.args('light'),
};
