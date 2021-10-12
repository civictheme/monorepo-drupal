import {
  boolean,
  date, number,
  radios,
  text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';
import { getSlots } from '../../00-base/base.stories';

import CivicEventCard from './event-card.twig';
import './event-card.scss';

export default {
  title: 'Molecule/Card',
  parameters: {
    layout: 'centered',
  },
};

export const EventCard = () => {
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
    date: date('Date', new Date(), generalKnobTab),
    title: text('Title', 'Event name which runs across two or three lines', generalKnobTab),
    location: text('Location', 'Suburb, State – 16:00–17:00', generalKnobTab),
    summary: text('Summary', 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..', generalKnobTab),
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
      max: 10,
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

  const html = CivicEventCard({
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
