// phpcs:ignoreFile
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';

import CivicThemePublicationCard from './publication-card.twig';
import {
  demoImage,
  getSlots,
  randomSentence, randomUrl,
} from '../../00-base/base.utils';

export default {
  title: 'Molecules/Publication Card',
  parameters: {
    layout: 'centered',
  },
};

export const PublicationCard = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const date = new Date().toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

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
      url: demoImage(),
      alt: 'Image alt text',
    } : false,
    file: boolean('With file', true, generalKnobTab) ? {
      url: randomUrl(),
      name: 'Document.doc',
      ext: 'doc',
      size: '42.88 KB',
      created: date,
      changed: date,
      icon: 'word-file',
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
