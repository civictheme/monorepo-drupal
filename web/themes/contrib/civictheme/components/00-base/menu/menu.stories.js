// phpcs:ignoreFile
import CivicThemeMenu from './menu.twig';
import getMenuLinks from './menu.utils';
import { knobRadios, knobText, shouldRender } from '../storybook/storybook.utils';

export default {
  title: 'Base/Utilities/Menu Generator',
  parameters: {
    layout: 'centered',
  },
};

export const MenuGenerator = (parentKnobs = {}) => {
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
    items: getMenuLinks({ knobTab: parentKnobs.knobTab }, null),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeMenu(knobs) : knobs;
};
