import {
  radios,
  object,
} from '@storybook/addon-knobs';
import CivicMenu from './menu.twig';
import './menu.scss';
import './dropdown-menu';

export default {
  title: 'Organisms/Menu',
  parameters: {
    layout: 'centered',
  },
};

export const MainMenu = () => {
  const links = [
    {
      title: 'About us',
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

  return CivicMenu({
    ...generalKnobs,
    menu_type: 'main',
  });
};

export const FooterMenu = () => {
  const links = [
    {
      title: 'For individuals',
      url: '#',
    },
    {
      title: 'For businesses',
      url: '#',
    },
    {
      title: 'For government',
      url: '#',
    },
    {
      title: 'Services',
      url: '#',
    },
    {
      title: 'News & events',
      url: '#',
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
  };

  return CivicMenu({
    ...generalKnobs,
    menu_type: 'footer',
  });
};
