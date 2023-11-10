// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import { getSlots, randomInt, randomSentence } from '../../00-base/base.utils';
import CivicThemeHeaderExample from './header.stories.twig';

import getMenuLinks from '../../00-base/menu/menu.utils';
import { Logo } from '../../02-molecules/logo/logo.stories';

export default {
  title: 'Organisms/Header',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Header = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const secondaryNavigationKnobTab = 'Secondary navigation';
  const primaryNavigationKnobTab = 'Primary navigation';
  const searchLinkKnobTab = 'Search';

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
    show_content_top2: boolean('Show slogan', true, generalKnobTab),
    show_content_top3: boolean('Show top content', true, generalKnobTab),
    show_content_middle3: boolean('Show middle content', true, generalKnobTab),
  };

  generalKnobs.logo = boolean('Show logo', true, generalKnobTab) ? Logo('Logo', false) : null;

  if (generalKnobs.show_content_middle3) {
    generalKnobs.primary_navigation_items = getMenuLinks(primaryNavigationKnobTab, (itemTitle, itemIndex, itemCurrentLevel, itemIsActiveTrail, itemParents) => `${itemTitle} ${itemParents.join('')}${itemIndex} ${randomSentence(itemCurrentLevel > 1 ? randomInt(2, 5) : randomInt(1, 3))}`);
    generalKnobs.primary_navigation_dropdown_columns = number(
      'Dropdown columns',
      4,
      {
        range: true,
        min: 0,
        max: 5,
        step: 1,
      },
      primaryNavigationKnobTab,
    );
    generalKnobs.primary_navigation_dropdown_columns_fill = boolean('Fill width for missing columns', false, primaryNavigationKnobTab);
    generalKnobs.with_search = boolean('With Search', true, generalKnobTab) ? {
      text: text('Text', 'Search', searchLinkKnobTab),
      url: text('Url', '/search', searchLinkKnobTab),
    } : null;
  }

  if (generalKnobs.show_content_top3) {
    generalKnobs.secondary_navigation_items = getMenuLinks(secondaryNavigationKnobTab, (itemTitle, itemIndex, itemCurrentLevel, itemIsActiveTrail, itemParents) => `${itemTitle} ${itemParents.join('')}${itemIndex} ${randomSentence(itemCurrentLevel > 1 ? randomInt(2, 5) : randomInt(1, 3))}`);
  }

  return CivicThemeHeaderExample({
    ...generalKnobs,
    ...getSlots([
      'content_top1',
      'content_top2',
      'content_top3',
      'content_middle1',
      'content_middle2',
      'content_middle3',
      'content_bottom1',
    ]),
  });
};
