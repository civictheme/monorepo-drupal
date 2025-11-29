import Component from './event-card.twig';

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
    attributes: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const EventCard = {
  parameters: {
    layout: 'centered',
  },
  args: {
    content_top: '',
    image_over: '',
    content_middle: '',
    content_bottom: '',
    theme: 'light',
    date: '20 Jan 2023 11:00',
    date_iso: '',
    date_end: '21 Jan 2023 15:00',
    date_end_iso: '',
    title: 'Event name which runs across two or three lines',
    location: 'Suburb, State – 16:00–17:00',
    summary: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
    link: {
      url: 'https://example.com/event',
      is_new_window: false,
    },
    image: {
      url: './demo/images/demo1.jpg',
      alt: 'Image alt text',
    },
    tags: [
      'Tag 1',
      'Tag 2',
    ],
    modifier_class: '',
    attributes: '',
  },
};
