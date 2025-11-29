import MobileNavigationPanel from './mobile-navigation.twig';
import MobileNavigationTrigger from './mobile-navigation-trigger.twig';

const meta = {
  title: 'Organisms/Navigation/Mobile Navigation',
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    top_menu: {
      control: { type: 'array' },
    },
    bottom_menu: {
      control: { type: 'array' },
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

export const MobileNavigation = {
  parameters: {
    layout: 'centered',
    viewport: {
      defaultViewport: 'xs',
    },
  },
  args: {
    theme: 'light',
    content_top: '',
    top_menu: [
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
    bottom_menu: [
      {
        title: 'Menu item 1',
        url: 'https://example.com/menu-item-1',
        in_active_trail: false,
        is_expanded: false,
      },
      {
        title: 'Menu item 2',
        url: 'https://example.com/menu-item-2',
        in_active_trail: false,
        is_expanded: false,
      },
    ],
    content_bottom: '',
    modifier_class: '',
  },
  render: (args) => (`${
    MobileNavigationTrigger({
      theme: args.theme,
      icon: 'bars',
      text: 'Menu',
      modifier_class: '',
    })
  }${
    MobileNavigationPanel(args)
  }`),
};
