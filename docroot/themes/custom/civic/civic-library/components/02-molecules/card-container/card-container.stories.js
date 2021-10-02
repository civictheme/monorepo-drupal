import {
  boolean, radios, text, select,
} from '@storybook/addon-knobs';
import CivicCardContainer from './card-container.stories.twig';
import imageFile from '../../../assets/image.png';
import './card-container.scss';

export default {
  title: 'Molecule/Card Container',
};

export const CardContainer = () => {
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
    title: text('Title', 'Card container title', generalKnobTab),
    link_text: text('Link Text', 'View all', generalKnobTab),
    url: text('Link URL', 'https://google.com', generalKnobTab),
    columns: select('Columns', [1, 2, 3, 4], 1, generalKnobTab),
    fill_width: boolean('With width', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  const EventKnob = {
    cards: boolean('With Events', true, generalKnobTab) ? {
      number_of_cards: select('Number of cards', [1, 2, 3, 4, 5, 6, 7, 8], 4, generalKnobTab),
      date: new Date(),
      title: 'Event name which runs across two or three lines',
      location: 'Suburb, State – 16:00–17:00',
      summary: 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..',
      tag: 'Topic/industry tag',
      image: {
        src: imageFile,
        alt: 'Image alt text',
      },
    } : false,
  };

  return CivicCardContainer({
    ...generalKnobs,
    ...EventKnob,
  });
};
