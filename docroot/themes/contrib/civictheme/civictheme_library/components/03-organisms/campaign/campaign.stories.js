// phpcs:ignoreFile
import {
  date, number, radios, text,
} from '@storybook/addon-knobs';
import {
  demoImage,
  getSlots, randomLinks, randomSentence,
  randomTags,
} from '../../00-base/base.utils';

import CivicThemeCampaign from './campaign.twig';

export default {
  title: 'Organisms/Campaign',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Campaign = (knobTab) => {
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
    title: text('Title', 'Campaign heading which runs across two or three lines', generalKnobTab),
    content: text('Content', randomSentence(), generalKnobTab),
    date: date('Date', new Date(), generalKnobTab),
    image: {
      url: demoImage(),
      alt: 'Image alt text',
    },
    image_position: radios(
      'Image position',
      {
        Left: 'left',
        Right: 'right',
      },
      'left',
      generalKnobTab,
    ),
    links: randomLinks(number(
      'Number of links',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ), 10),
    tags: randomTags(number(
      'Number of tags',
      1,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ), true),
    vertical_spacing: radios(
      'Vertical spacing',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  return CivicThemeCampaign({
    ...generalKnobs,
    ...getSlots([
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });
};
