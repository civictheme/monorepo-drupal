// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicThemePublicationCard from './publication-card.twig';
import { demoImage, getSlots, randomUrl } from '../../00-base/base.stories';

export default {
  title: 'Molecules/Card/Publication Card',
  parameters: {
    layout: 'centered',
  },
};

export const PublicationCard = (knobTab) => {
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
    title: text('Title', 'Publication or whitepaper main title.', generalKnobTab),
    summary: text('Summary', 'Publication summary', generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: demoImage(),
      alt: 'Image alt text',
    } : false,
    link: boolean('With file', true, generalKnobTab) ? {
      url: 'https://file-examples-com.github.io/uploads/2017/02/file-sample_100kB.doc',
      text: 'Filename.pdf (175.96KB)',
    } : null,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const { icons } = ICONS;
  const withIcon = boolean('With icon', false, iconKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', icons, 'business_calendar', iconKnobTab) : null,
  };

  const html = CivicThemePublicationCard({
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
