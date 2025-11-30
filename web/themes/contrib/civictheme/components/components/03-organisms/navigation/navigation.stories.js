// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeNavigation from './navigation.twig';
import getMenuLinks from '../../00-base/menu/menu.utils';
import { randomInt, randomSentence } from '../../00-base/base.utils';

export default {
  title: 'Organisms/Navigation',
  parameters: {
    layout: 'centered',
  },
};

export const Navigation = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    title: text('Title', 'Navigation title', generalKnobTab),
    type: radios(
      'Type',
      {
        None: 'none',
        Inline: 'inline',
        Dropdown: 'dropdown',
        Drawer: 'drawer',
      },
      'none',
      generalKnobTab,
    ),
    items: getMenuLinks('Links', (itemTitle, itemIndex, itemCurrentLevel, itemIsActiveTrail, itemParents) => `${itemTitle} ${itemParents.join('')}${itemIndex} ${randomSentence(itemCurrentLevel > 1 ? randomInt(2, 5) : randomInt(1, 3))}`),
    is_animated: boolean('Animated', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeNavigation({
    ...generalKnobs,
  });
};
