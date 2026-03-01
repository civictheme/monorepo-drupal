// phpcs:ignoreFile
export default {
  args: (theme = 'light') => ({
    theme,
    name: '',
    title: 'Navigation title',
    type: 'none',
    variant: 'none',
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
    dropdown_columns: 1,
    dropdown_columns_fill: false,
    is_animated: false,
    menu_id: 'navigation',
    attributes: null,
    modifier_class: '',
  }),
};
