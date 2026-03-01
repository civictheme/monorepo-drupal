// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
    heading_id: 'ct-pagination-demo',
    items: {
      previous: {
        href: 'http://example.com',
      },
      pages: {
        1: { href: 'http://example.com' },
        2: { href: 'http://example.com' },
        3: { href: 'http://example.com' },
      },
      next: {
        href: 'http://example.com',
      },
    },
    items_modifier_class: '',
    current: 1,
    total_pages: 3,
    items_per_page_title: 'Items per page',
    items_per_page_options: [
      {
        type: 'option',
        label: 10,
        value: 10,
        is_selected: false,
      },
      {
        type: 'option',
        label: 20,
        value: 20,
        is_selected: true,
      },
      {
        type: 'option',
        label: 50,
        value: 50,
        is_selected: false,
      },
      {
        type: 'option',
        label: 100,
        value: 100,
        is_selected: false,
      },
    ],
    items_per_page_name: 'items_per_page_name',
    items_per_page_id: 'items-per-page-id',
    items_per_page_attributes: null,
    use_ellipsis: false,
    modifier_class: '',
    attributes: null,
  }),
};
