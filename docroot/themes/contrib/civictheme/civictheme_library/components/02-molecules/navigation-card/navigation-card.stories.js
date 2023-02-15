// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicThemeNavigationCard from './navigation-card.twig';
import {
  demoImage,
  getSlots,
  randomUrl,
} from '../../00-base/base.utils';

export default {
  title: 'Molecules/Navigation Card',
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
    title: text('Title', 'Navigation card heading which runs across two or three lines', generalKnobTab),
    summary: text('Summary', 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.', generalKnobTab),
    link: {
      url: text('Link URL', randomUrl(), generalKnobTab),
      is_external: boolean('Link is external', false, generalKnobTab),
      is_new_window: boolean('Open in a new window', false, generalKnobTab),
    },
    image: boolean('With image', true, generalKnobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : false,
    image_as_icon: boolean('Image as icon', false, generalKnobTab),
    modifier_class: `story-wrapper-size--medium ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = boolean('With icon', false, generalKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab) : null,
  };

  return CivicThemeNavigationCard({
    ...generalKnobs,
    ...iconKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });
};
