import { radios, text } from '@storybook/addon-knobs';
import CivicSecondaryNavigation from './secondary-navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Secondary Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const SecondaryNavigation = () => {
  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    items: getMenuLinks(),
    modifier_class: text('Additional class', ''),
    attributes: text('Additional attributes', ''),
  };

  return CivicSecondaryNavigation({
    ...generalKnobs,
  });
};
