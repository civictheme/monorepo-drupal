// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
    title: 'Navigation card heading which runs across two or three lines',
    summary: 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.',
    link: {
      text: 'Link',
      url: 'https://example.com',
      is_new_window: false,
      is_external: false,
    },
    image: {
      url: './demo/images/demo1.jpg',
      alt: 'Image alt text',
    },
    image_as_icon: false,
    icon: '', // replace with actual icon value
    is_title_click: false,
    image_over: '',
    content_top: '',
    content_middle: '',
    content_bottom: '',
    modifier_class: '',
    attributes: null,
  }),
};
