import { boolean, date, radios, text } from '@storybook/addon-knobs'
import imageFile from '../../../assets/image.png';

import CivicEventCard from './event-card.twig'
import './event-card.scss'

export default {
  title: 'Molecule/Event Card'
}

export const EventCard = () => {
  const eventCardKnobs = {
    theme: radios('Theme', {
      'Light': 'light',
      'Dark': 'dark'
    }, 'light'),
    with_image: boolean('With image', true),
    image: {
      src: text('Image path', imageFile),
      alt: text('Image alt text', 'Civic image alt')
    },
    content_top: boolean('show content top', false),
    content_top_label: text('content top label', 'content top label'),
    date: date('Date', new Date()),
    title: text('Title', 'Event name which runs across two or three lines'),
    location: text('Location', 'Suburb, State – 16:00–17:00'),
    summary: text('Summary', 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..'),
    tag: text('Topic/industry tag', 'Topic/industry tag'),
    url: text('Card URL', 'https://google.com'),
    modifier_class: text('Additional class', ''),
  };

  eventCardKnobs.date = new Date(eventCardKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });

  return CivicEventCard(eventCardKnobs);
}


