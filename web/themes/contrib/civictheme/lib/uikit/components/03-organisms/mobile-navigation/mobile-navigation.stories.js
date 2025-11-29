// phpcs:ignoreFile
import { knobRadios, knobSelect, knobText, shouldRender, slotKnobs } from '../../00-base/storybook/storybook.utils';
import getMenuLinks from '../../00-base/menu/menu.utils';
import CivicThemeMobileNavigation from './mobile-navigation.twig';
import CivicThemeMobileNavigationTrigger from './mobile-navigation-trigger.twig';

export default {
  title: 'Organisms/Navigation/Mobile Navigation',
  parameters: {
    layout: 'fullscreen',
    storyLayoutSize: 'small',
    storyLayoutClass: 'story-container__page-content story-ct-mobile-navigation',
    docs: 'Click on the mobile navigation trigger in the top left to open Mobile Navigation panel.',
    docsPlacement: 'after',
  },
};

export const MobileNavigation = (parentKnobs = {}) => {
  const theme = knobRadios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    parentKnobs.theme,
    parentKnobs.knobTab,
  );

  const knobs = {
    theme,
    trigger_theme: knobRadios(
      'Trigger Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.trigger_theme,
      parentKnobs.knobTab,
    ),
    trigger_text: knobText('Trigger Text', 'Menu', parentKnobs.trigger_text, parentKnobs.knobTab),
    trigger_icon: knobSelect('Trigger Icon', Object.values(ICONS), 'bars', parentKnobs.trigger_icon, parentKnobs.knobTab),
    top_menu: getMenuLinks({ knobTab: 'Top menu' }, 'Top '),
    bottom_menu: getMenuLinks({ knobTab: 'Bottom menu' }, 'Bottom '),
  };

  const trigger = CivicThemeMobileNavigationTrigger({
    theme,
    icon: knobs.trigger_icon,
    text: knobs.trigger_text,
  });

  const panel = CivicThemeMobileNavigation({
    theme,
    content_top: knobs.content_top,
    top_menu: knobs.top_menu,
    bottom_menu: knobs.bottom_menu,
    content_bottom: knobs.content_bottom,
    ...slotKnobs([
      'content_top',
      'content_bottom',
    ]),
  });

  return shouldRender(parentKnobs) ? `${trigger}${panel}` : knobs;
};
