// phpcs:ignoreFile
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';

import CivicThemePublicationCard from './publication-card.twig';
import {
  demoImage,
  getSlots,
  randomSentence,
} from '../../00-base/base.utils';

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
    title: text('Title', 'Publication or whitepaper main title', generalKnobTab),
    summary: text('Summary', randomSentence(), generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: demoImage(),
      alt: 'Image alt text',
    } : false,
    link: boolean('With file', true, generalKnobTab) ? {
      url: 'https://file-examples-com.github.io/uploads/2017/02/file-sample_100kB.doc',
      text: 'Filename (PDF, 175.96 KB)',
    } : null,
    modifier_class: `story-wrapper-size--medium ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemePublicationCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });
};
