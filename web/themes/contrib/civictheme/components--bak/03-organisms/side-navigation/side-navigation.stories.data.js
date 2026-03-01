// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
    title: 'Side Navigation title',
    items: [
      {
        title: 'Menu item 1',
        url: 'https://example.com/menu-item-1',
        in_active_trail: false,
        is_expanded: false,
        below: [
          {
            title: 'Menu subitem 1',
            url: 'https://example.com/menu-item-1',
            in_active_trail: false,
            is_expanded: false,
            below: false,
          },
          {
            title: 'Menu subitem 2',
            url: 'https://example.com/menu-item-1',
            in_active_trail: false,
            is_expanded: false,
            below: [
              {
                title: 'Menu subsubitem 1',
                url: 'https://example.com/menu-item-1',
                in_active_trail: false,
                is_expanded: false,
                below: false,
              },
              {
                title: 'Menu subsubitem 2',
                url: 'https://example.com/menu-item-1',
                in_active_trail: false,
                is_expanded: false,
                below: false,
              },
            ],
          },
        ],
      },
      {
        title: 'Menu item 2',
        url: 'https://example.com/menu-item-2',
        in_active_trail: false,
        is_expanded: false,
      },
    ],
    vertical_spacing: 'none',
    attributes: null,
    modifier_class: '',
  }),
};
