import {
  boolean, radios, text, select,
} from '@storybook/addon-knobs';
import CivicCardContainer from './card-container.twig';
import imageFile from '../../../assets/image.png';
import './card-container.scss';

export default {
  title: 'Organism/Card Container',
};

export const CardContainer = () => {
  const generalKnobTab = 'General';
  const cardsKnobTab = 'Cards';

  const typeOfCard = radios(
    'Type of card',
    {
      Promo: 'promo-card',
      Event: 'event-card',
      Navigation: 'navigation-card',
      Service: 'service-card',
    },
    'promo-card',
    generalKnobTab,
  );
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
    column_count: select('Columns', [1, 2, 3, 4], 1, generalKnobTab),
    fill_width: boolean('With width', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  generalKnobs.date = new Date(generalKnobs.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  return CivicCardContainer({
    ...generalKnobs,
  });
};
