import {
  boolean, date, radios, text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';
import { getSlots } from '../../00-base/base.stories';

import CivicEventCard from './event-card.twig';
import './event-card.scss';

export default {
  title: 'Molecule/Event Card',
  component: CivicEventCard,
  argTypes: {
    theme: {
      name: 'Theme',
      options: {
        Light: 'light',
        Dark: 'dark',
      },
      control: { type: 'radio' }, // Automatically inferred when 'options' is defined
    },
  },
  parameters: {
    layout: 'centered',
  },
};

const generalKnobTab = 'General';

const generalKnobs = {
  theme: 'light',
  title: 'Event name which runs across two or three lines',
  summary: 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..',
  date: new Date(),
  location: 'Suburb, State – 16:00–17:00',
  tags: [
    'Topic/industry tag',
  ],
  url: text('Link URL', 'https://google.com', generalKnobTab),
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

export const EventCard = CivicEventCard.bind({});
EventCard.args = {
  ...generalKnobs,
  ...getSlots([
    'image_over',
    'content_top',
    'content_middle',
    'content_bottom',
  ]),
};
