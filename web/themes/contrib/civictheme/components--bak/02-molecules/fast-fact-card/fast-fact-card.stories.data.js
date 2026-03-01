// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
    title: '4',
    summary: 'CivicTheme websites',
    link: {
      url: 'https://example.com',
      is_new_window: false,
    },
    image: {
      url: './demo/images/demo1.jpg',
      alt: 'Image alt text',
    },
    is_title_click: false,
    modifier_class: '',
    attributes: null,
  }),
};
