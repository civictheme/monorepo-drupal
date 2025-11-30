// phpcs:ignoreFile
import CivicThemeNavigation from './navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';
import { knobBoolean, knobRadios, knobText, randomInt, randomSentence, shouldRender } from '../../00-base/storybook/storybook.utils';

export default {
  title: 'Organisms/Navigation/Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const Navigation = (parentKnobs = {}) => {
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
    title: knobText('Title', 'Navigation title', parentKnobs.title, parentKnobs.knobTab),
    type: knobRadios(
      'Type',
      {
        None: 'none',
        Inline: 'inline',
        Dropdown: 'dropdown',
        Drawer: 'drawer',
      },
      'none',
      parentKnobs.type,
      parentKnobs.knobTab,
    ),
    items: getMenuLinks({ knobTab: 'Links' }, (itemTitle, itemIndex, itemCurrentLevel, itemIsActiveTrail, itemParents) => `${itemTitle} ${itemParents.join('')}${itemIndex} ${randomSentence(itemCurrentLevel > 1 ? randomInt(2, 5) : randomInt(1, 3))}`),
    is_animated: knobBoolean('Animated', true, parentKnobs.is_animated, parentKnobs.knobTab),
    modifier_class: knobText('Additional class', '', parentKnobs.modifier_class, parentKnobs.knobTab),
    attributes: knobText('Additional attributes', '', parentKnobs.attributes, parentKnobs.knobTab),
  };

  return shouldRender(parentKnobs) ? CivicThemeNavigation(knobs) : knobs;
};
