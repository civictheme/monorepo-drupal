import { radios, text } from '@storybook/addon-knobs';
import CivicNavigation from './navigation.twig';
import getMenuLinks from './../../02-molecules/menu/menu.utils';

export default {
  title: 'Organisms/Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const Navigation = () => {
  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    title: text('Title', 'Navigation title'),
    items: getMenuLinks(),
    modifier_class: text('Additional class', ''),
    attributes: text('Additional attributes', ''),
  };

  return CivicNavigation({
    ...generalKnobs,
  });
};
