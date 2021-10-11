import {
  boolean,
  number,
  radios,
  text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';
import { getSlots } from '../../00-base/base.stories';

import CivicPromoCard from './promo-card.twig';

export default {
  title: 'Molecule/Card',
  parameters: {
    layout: 'centered',
  },
};

export const PromoCard = () => {
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
    title: text('Title', 'Promo name which runs across two or three lines', generalKnobTab),
    summary: text('Summary', 'Bring to the table win-win survival strategies to ensure proactive domination. At the end of the day, going forward, a new normal that has evolved from generation X is on the runway heading towards a streamlined cloud solution. User generated content in real-time will have multiple touchpoints for offshoring.', generalKnobTab),
    date: text('Date', '1 Jun 1970', generalKnobTab),
    url: text('Link URL', 'http://example.com', generalKnobTab),
    image: boolean('With image', true, generalKnobTab) ? {
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  // Adding dynamic promo card tags.
  const tagKnobTab = 'Tags';
  const tagNum = number(
    'Number of tags (0-4)',
    1,
    {
      range: true,
      min: 0,
      max: 4,
      step: 1,
    },
    tagKnobTab,
  );

  // Adding dynamic number of tags.
  const tags = {};
  let itr = 1;
  while (itr <= tagNum) {
    tags[`tag${itr}`] = text(`tag${itr}`, `Topic ${itr}`, tagKnobTab);
    itr += 1;
  }
  const tagKnobs = {
    tags,
    tagNum,
  };

  const html = CivicPromoCard({
    ...generalKnobs,
    ...tagKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });

  return `<div class="story-wrapper-size--small">${html}</div>`;
};
