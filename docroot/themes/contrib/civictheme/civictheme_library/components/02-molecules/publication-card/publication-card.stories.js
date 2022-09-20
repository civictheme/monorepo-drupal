// phpcs:ignoreFile
import {
  boolean, radios, select, text,
} from '@storybook/addon-knobs';

import CivicThemePublicationCard from './publication-card.twig';
import {
  demoImage,
  getSlots,
  randomSentence,
  randomUrl,
} from '../../00-base/base.stories';

export default {
  title: 'Molecules/Cards/Publication Card',
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
    summary: text('Summary', randomSentence(), generalKnobTab),
    url: text('URL', randomUrl(), generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: demoImage(),
      alt: 'Image alt text',
    } : false,
    link: boolean('With file', true, generalKnobTab) ? {
      url: 'https://file-examples-com.github.io/uploads/2017/02/file-sample_100kB.doc',
      text: 'Filename.pdf (175.96KB)',
    } : null,
    modifier_class: `story-wrapper-size--medium ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const iconKnobTab = 'Icon';
  const withIcon = boolean('With icon', false, iconKnobTab);
  const iconKnobs = {
    icon: withIcon ? select('Icon', Object.values(ICONS), Object.values(ICONS)[0], iconKnobTab) : null,
  };

  return CivicThemePublicationCard({
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
