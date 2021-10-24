// @todo This is a placeholder component. It is not finished!
import { radios, text } from '@storybook/addon-knobs';
import CivicSideNavigation from './side-navigation.twig';
import getMenuLinks from '../../02-molecules/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Side Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const SideNavigation = () => {
  const generalKnobs = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    title: text('Title', 'Side Navigation title'),
    items: getMenuLinks(),
    modifier_class: text('Additional class', ''),
    attributes: text('Additional attributes', ''),
  };

  return CivicSideNavigation({
    ...generalKnobs,
  });
};
