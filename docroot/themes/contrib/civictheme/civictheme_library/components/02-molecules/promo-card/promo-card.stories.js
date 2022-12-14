// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import {
  demoImage,
  getSlots, randomSentence,
  randomTags,
  randomUrl,
} from '../../00-base/base.stories';

import CivicThemePromoCard from './promo-card.twig';

export default {
  title: 'Molecules/Cards/Promo Card',
  parameters: {
    layout: 'centered',
  },
};

export const PromoCard = (knobTab) => {
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
    subtitle: text('Subtitle', randomSentence(3), generalKnobTab),
    title: text('Title', 'Promo card name which runs across two or three lines', generalKnobTab),
    summary: text('Summary', randomSentence(), generalKnobTab),
    url: text('Link URL', randomUrl(), generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: demoImage(),
      alt: 'Image alt text',
    } : false,
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
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  return CivicThemePromoCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });
};
