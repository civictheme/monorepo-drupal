import { radios } from '@storybook/addon-knobs';
import CivicMobileNavigation from './mobile-navigation.twig';
import './mobile-navigation.scss';
import '../../02-molecules/flyout/flyout';
import '../../02-molecules/flyout/flyout.scss';
import getMenuLinks from '../../02-molecules/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Mobile Navigation',
  parameters: {
    layout: 'fullscreen',
  },
};

export const MobileNavigation = () => {
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
    items: getMenuLinks(),
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
