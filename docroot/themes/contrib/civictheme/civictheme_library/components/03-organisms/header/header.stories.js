import {
  boolean, number, radios,
} from '@storybook/addon-knobs';
import { getSlots } from '../../00-base/base.stories';
import CivicThemeHeaderExample from './header.stories.twig';

import getMenuLinks from '../../00-base/menu/menu.utils';

export default {
  title: 'Organisms/Header',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Header = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const primaryNavigationKnobTab = 'Primary navigation';
  const secondaryNavigationKnobTab = 'Secondary navigation';

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
    show_content_top3: boolean('Show Secondary Navigation', true, generalKnobTab),
    show_content_middle3: boolean('Show Primary Navigation', true, generalKnobTab),
  };

  generalKnobs.logos = boolean('Show Logo', true, generalKnobTab) ? {
    mobile: {
      src: LOGOS.mobile[generalKnobs.theme],
      alt: 'Logo mobile alt text',
    },
    desktop: {
      src: LOGOS.desktop[generalKnobs.theme],
      alt: 'Logo desktop alt text',
    },
  } : null;

  if (generalKnobs.show_content_top3) {
    generalKnobs.secondary_navigation_items = getMenuLinks(secondaryNavigationKnobTab, 'Secondary ');
  }

  if (generalKnobs.show_content_middle3) {
    generalKnobs.primary_navigation_items = getMenuLinks(primaryNavigationKnobTab, 'Primary ');
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
