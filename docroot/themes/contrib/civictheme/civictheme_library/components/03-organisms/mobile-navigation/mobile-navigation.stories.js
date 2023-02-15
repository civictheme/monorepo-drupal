// phpcs:ignoreFile
import { radios, select, text } from '@storybook/addon-knobs';
import CivicThemeMobileNavigationExample from './mobile-navigation.stories.twig';
import { getSlots } from '../../00-base/base.utils';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Mobile Navigation',
  parameters: {
    layout: 'fullscreen',
  },
};

export const MobileNavigation = () => {
  const generalKnobTab = 'Menu';
  const topMenuKnobTab = 'Top menu';
  const bottomMenuKnobTab = 'Bottom menu';

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
    trigger_theme: radios(
      'Trigger Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    trigger_text: text('Trigger Text', 'Menu', generalKnobTab),
    trigger_icon: select('Trigger Icon', Object.values(ICONS), 'bars', generalKnobTab),
    top_menu: getMenuLinks(topMenuKnobTab, 'Top '),
    bottom_menu: getMenuLinks(bottomMenuKnobTab, 'Bottom '),
  };

  return CivicThemeMobileNavigationExample({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
