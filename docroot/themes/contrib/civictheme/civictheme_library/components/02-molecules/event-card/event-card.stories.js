import {
  boolean, date, number, radios, text,
} from '@storybook/addon-knobs';
import {
  demoImage,
  getSlots,
  randomTags,
  randomUrl,
} from '../../00-base/base.stories';

import CivicThemeEventCard from './event-card.twig';

export default {
  title: 'Molecules/Card/Event Card',
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
    date: date('Date', new Date(), generalKnobTab),
    title: text('Title', 'Event name which runs across two or three lines', generalKnobTab),
    location: text('Location', 'Suburb, State – 16:00–17:00', generalKnobTab),
    summary: text('Summary', 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..', generalKnobTab),
    url: text('Link URL', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
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
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  const html = CivicThemeEventCard({
    ...generalKnobs,
    ...getSlots([
      'image_over',
      'content_top',
      'content_middle',
      'content_bottom',
    ]),
  });

  return `${html}`;
};
