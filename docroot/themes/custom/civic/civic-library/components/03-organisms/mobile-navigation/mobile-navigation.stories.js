import {
  radios,
  object,
  text,
} from '@storybook/addon-knobs';
import CivicMobileNavigation from './mobile-navigation.twig';
import './mobile-navigation.scss';

export default {
  title: 'Organisms/Mobile Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const MobileNavigation = () => {
  const links = [
    {
      title: 'About us',
      url: '#',
      below: [
        {
          title: 'Test Second Level',
          url: '#',
          below: [
            {
              title: 'Test Third Level',
              url: 'https://www.google.com',
            },
          ],
        },
        {
          title: 'Menu item',
          url: '#',
        },
        {
          title: 'Menu item',
          url: '#',
        },
        {
          title: 'Menu item',
          url: '#',
        },
      ],
    },
    {
      title: 'Help',
      url: '#',
      below: [
        {
          title: 'Menu item',
          url: '#',
        },
        {
          title: 'Menu item',
          url: '#',
        },
        {
          title: 'Menu item',
          url: '#',
        },
      ],
    },
    {
      title: 'Services',
      url: '#',
      below: [
        {
          title: 'External link',
          url: '#',
          new_window: true,
          is_external: true,
        },
        {
          title: 'External link',
          url: '#',
          new_window: true,
          is_external: true,
        },
        {
          title: 'External link',
          url: '#',
          new_window: true,
          is_external: true,
        },
      ],
    },
    {
      title: 'Contact Us',
      url: 'https://www.example.com',
    },
  ];

  const generalKnobTab = 'General';

  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    items: object('Links', links, generalKnobTab),
    icon_size: radios(
      'Icon Size',
      {
        Small: 'small',
        Regular: 'regular',
      },
      'regular',
      generalKnobTab,
    ),
  };

  return CivicMobileNavigation({
    ...generalKnobs,
    menu_type: 'main',
  });
};
