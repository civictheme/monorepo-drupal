import { radios, text } from '@storybook/addon-knobs';
import CivicThemePrimaryNavigation from './primary-navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Primary Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const PrimaryNavigation = () => {
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

  return CivicThemePrimaryNavigation({
    ...generalKnobs,
  });
};
