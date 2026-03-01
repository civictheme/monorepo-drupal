// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
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
    is_title_click: false,
    image: {
      url: './demo/images/demo7.jpg',
      alt: 'Image alt text',
    },
    tags: [
      'Tag 1',
      'Tag 2',
    ],
    modifier_class: '',
    attributes: null,
    content_top: '',
    image_over: '',
    content_middle: '',
    content_bottom: '',
  }),
};
