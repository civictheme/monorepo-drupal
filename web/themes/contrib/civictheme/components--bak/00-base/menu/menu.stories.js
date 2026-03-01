// phpcs:ignoreFile
/**
 * CivicTheme Menu component stories.
 */

import Component from './menu.twig';

const meta = {
  title: 'Base/Utilities/Menu',
  component: Component,
  argTypes: {
    theme: {
      control: { type: 'radio' },
      options: ['light', 'dark'],
    },
    items: {
      control: { type: 'object' },
    },
    is_collapsible: {
      control: { type: 'boolean' },
    },
    modifier_class: {
      control: { type: 'text' },
    },
  },
};

export default meta;

export const Menu = {
  parameters: {
    layout: 'centered',
  },
  args: {
    theme: 'light',
    items: [
      {
        title: 'Link 1',
        url: '#',
        below: [
          {
            title: 'Sublink 1',
            url: '#',
            below: [
              {
                title: 'Subsublink 1',
                url: '#',
                below: [],
                is_expanded: false,
                in_active_trail: false,
              },
              {
                title: 'Subsublink 2',
                url: '#',
                below: [],
                is_expanded: false,
                in_active_trail: false,
              },
            ],
            is_expanded: false,
            in_active_trail: false,
          },
          {
            title: 'Sublink 2',
            url: '#',
            below: [],
            is_expanded: false,
            in_active_trail: false,
          },
        ],
        is_expanded: false,
        in_active_trail: false,
      },
      {
        title: 'Link 2',
        url: '#',
        below: [],
        is_expanded: false,
        in_active_trail: false,
      },
      {
        title: 'Link 3',
        url: '#',
        below: [
          {
            title: 'Sublink 3',
            url: '#',
            below: [
              {
                title: 'Subsublink 3',
                url: '#',
                below: [],
                is_expanded: false,
                in_active_trail: false,
              },
            ],
            is_expanded: false,
            in_active_trail: false,
          },
        ],
        is_expanded: false,
        in_active_trail: false,
      },
    ],
    is_collapsible: false,
    modifier_class: '',
  },
};
