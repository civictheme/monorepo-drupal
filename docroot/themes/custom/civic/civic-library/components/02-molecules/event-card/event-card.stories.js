import { boolean, date, object, radios, text } from '@storybook/addon-knobs'

import CivicEventCard from './event-card.twig'
import './event-card.scss'

export default {
  title: 'Molecule/Cards'
}


export const EventCard = () => {
  const imageData = {
    'src': 'https://via.placeholder.com/376x240',
    'alt': 'image alt text'
  };

  // Current component parameters.  
  const eventCardParams = {
    theme: radios('Theme', {
      'Light': 'light',
      'Dark': 'dark'
    }, 'light'),
    with_image: boolean('With image', true),
    image: object('Image  (Applies to card with image.)', imageData),
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

  // Date format/
  let options = {
    year: "numeric",
    month: "short",
    day: "numeric"
  };
   
  eventCardParams.date = new Date(eventCardParams.date).toLocaleDateString("en-uk", options);

  return CivicEventCard(eventCardParams);
}


