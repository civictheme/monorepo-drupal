import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';

import CivicNavigationCard from './navigation-card.twig';
import './navigation-card.scss';
import { getSlots } from '../../00-base/base.stories';

export default {
  title: 'Molecule/Card',
  parameters: {
    layout: 'centered',
  },
};

export const NavigationCard = () => {
  const generalKnobTab = 'General';
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
    summary: text('Summary', 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.', generalKnobTab),
    url: text('URL', 'http://example.com', generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const { icons } = ICONS;
  const withIcon = boolean('With icon', false, iconKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', icons, 'business_calendar', iconKnobTab) : null,
  };

  return CivicNavigationCard({
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
