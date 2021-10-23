import { radios, text } from '@storybook/addon-knobs';
import CivicMenu from './menu.twig';
import getMenuLinks from './menu.utils';

export default {
  title: 'Molecule/Menu',
  parameters: {
    layout: 'centered',
  },
};

export const Menu = () => {
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
  };

  return CivicMenu({
    ...generalKnobs,
  });
};
