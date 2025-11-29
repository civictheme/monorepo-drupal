// phpcs:ignoreFile
import CivicThemeSideNavigation from './side-navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';
import { knobRadios, knobText, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Organisms/Navigation/Side Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const SideNavigation = (parentKnobs = {}) => {
  const knobs = {
    theme: knobRadios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      parentKnobs.theme,
      parentKnobs.knobTab,
    ),
    title: knobText('Title', 'Side Navigation title', parentKnobs.title, parentKnobs.knobTab),
    items: getMenuLinks({ knobTab: 'Links' }),
    vertical_spacing: knobRadios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      parentKnobs.vertical_spacing,
      parentKnobs.knobTab,
    ),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeSideNavigation(knobs) : knobs;
};
