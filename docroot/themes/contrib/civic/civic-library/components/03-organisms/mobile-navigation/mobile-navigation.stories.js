import { radios, select, text } from '@storybook/addon-knobs';
import CivicMobileNavigationExample from './mobile-navigation.stories.twig';
import { getSlots } from '../../00-base/base.stories';
import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Navigation/Mobile Navigation',
  parameters: {
    layout: 'fullscreen',
  },
};

export const MobileNavigation = () => {
  const generalKnobTab = 'Menu';
  const topMenuKnobTab = 'Top menu';
  const bottomMenuKnobTab = 'Bottom menu';

  const { icons } = ICONS;
  const defaultIcon = icons.indexOf('content_justifyalignment');

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
    trigger_icon: select('Trigger Icon', icons, defaultIcon !== -1 ? icons[defaultIcon] : icons[0], generalKnobTab),
    top_menu: getMenuLinks(topMenuKnobTab, 'Top '),
    bottom_menu: getMenuLinks(bottomMenuKnobTab, 'Bottom '),
  };

  return CivicMobileNavigationExample({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
