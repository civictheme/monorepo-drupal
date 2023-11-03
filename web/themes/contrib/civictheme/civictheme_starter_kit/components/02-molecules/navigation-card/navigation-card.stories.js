// phpcs:ignoreFile
/**
 * @file
 * Navigation Card stories.
 *
 * This is copied from the CivicTheme's Navigation Card story and updated
 * with a new 'tags' property.
 */

import {
  boolean, number, radios, select, text,
} from '@storybook/addon-knobs';

import CivicThemeNavigationCard from './navigation-card.twig';
import {
  demoImage,
  getSlots,
  randomTags,
  randomUrl,
} from '../../00-base/base.utils';

export default {
  title: 'Molecules/Card/Navigation Card',
  parameters: {
    layout: 'centered',
  },
};

export const NavigationCard = (knobTab) => {
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
    size: radios(
      'Size',
      {
        Large: 'large',
        Small: 'small',
      },
      'large',
      generalKnobTab,
    ),
    title: text('Title', 'Navigation card heading which runs across two or three lines', generalKnobTab),
    summary: text('Summary', '', generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : false,
    // This is a new property added for this extended component.
    tags: randomTags(number(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ), true),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = boolean('With icon', false, iconKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab) : null,
  };

  const html = CivicThemeNavigationCard({
    ...generalKnobs,
    ...iconKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });

  return `<div class="story-wrapper-size--medium">${html}</div>`;
};
