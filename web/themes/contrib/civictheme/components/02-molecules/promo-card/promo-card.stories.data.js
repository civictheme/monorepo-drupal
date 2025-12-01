export default {
  args: (theme = 'light') => ({
    theme,
    subtitle: 'Subtitle',
    date: '20 Jan 2023 11:00',
    date_iso: '',
    date_end: '21 Jan 2023 15:00',
    date_end_iso: '',
    title: 'Promo card name which runs across two or three lines',
    summary: 'Summary',
    link: {
      url: 'https://example.com',
      is_external: false,
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
    content_top: '',
    image_over: '',
    content_middle: '',
    content_bottom: '',
    modifier_class: '',
    attributes: '',
  }),
};
