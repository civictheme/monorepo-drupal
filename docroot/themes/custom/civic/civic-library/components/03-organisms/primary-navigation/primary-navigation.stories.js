import { object, radios } from '@storybook/addon-knobs';
import CivicPrimaryNavigation from './primary-navigation.twig';
import './primary-navigation.scss';

export default {
  title: 'Organisms/Primary Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const PrimaryNavigation = () => {
  const links = [
    {
      title: 'About us',
      url: '#',
      below: [
        {
          title: 'PrimaryNavigation item',
          url: '#',
        },
        {
          title: 'PrimaryNavigation item',
          url: '#',
        },
        {
          title: 'PrimaryNavigation item',
          url: '#',
        },
        {
          title: 'PrimaryNavigation item',
          url: '#',
        },
      ],
    },
    {
      title: 'Help',
      url: '#',
      below: [
        {
          title: 'PrimaryNavigation item',
          url: '#',
        },
        {
          title: 'PrimaryNavigation item',
          url: '#',
        },
        {
          title: 'PrimaryNavigation item',
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
  };

  return CivicPrimaryNavigation({
    ...generalKnobs,
  });
};
