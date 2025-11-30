// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import {
  dateIsValid,
  demoImage,
  getSlots, randomSentence,
  randomTags,
  randomUrl,
} from '../../00-base/base.utils';

import CivicThemeEventCard from './event-card.twig';

export default {
  title: 'Molecules/Event Card',
  parameters: {
    layout: 'centered',
  },
};

export const EventCard = (knobTab) => {
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
    date: text('Date', '20 Jan 2023 11:00', generalKnobTab),
    date_end: text('End date', '21 Jan 2023 15:00', generalKnobTab),
    title: text('Title', 'Event name which runs across two or three lines', generalKnobTab),
    location: text('Location', 'Suburb, State – 16:00–17:00', generalKnobTab),
    summary: text('Summary', randomSentence(), generalKnobTab),
    link: {
      url: text('Link URL', randomUrl(), generalKnobTab),
      is_external: boolean('Link is external', false, generalKnobTab),
      is_new_window: boolean('Open in a new window', false, generalKnobTab),
    },
    image: boolean('With image', true, generalKnobTab) ? {
      url: demoImage(),
      alt: 'Image alt text',
    } : null,
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

  generalKnobs.date_iso = dateIsValid(generalKnobs.date) ? new Date(generalKnobs.date).toISOString() : null;
  generalKnobs.date_end_iso = dateIsValid(generalKnobs.date_end) ? new Date(generalKnobs.date_end).toISOString() : null;

  return CivicThemeEventCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });
};
