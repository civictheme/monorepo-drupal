import {
  boolean, radios, number, text, select,
} from '@storybook/addon-knobs';
import CivicCardContainer from './card-container.twig';
import { PromoCard } from '../../02-molecules/promo-card/promo-card.stories';
import { EventCard } from '../../02-molecules/event-card/event-card.stories';
import { NavigationCard } from '../../02-molecules/navigation-card/navigation-card.stories';
import { ServiceCard } from '../../02-molecules/service-card/service-card.stories';

import './card-container.scss';

const CardMap = {
  'promo-card': PromoCard,
  'event-card': EventCard,
  'navigation-card': NavigationCard,
  'service-card': ServiceCard,
};

export default {
  title: 'Organism/Card Container',
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
    column_count: select('Columns', [1, 2, 3, 4], 1, generalKnobTab),
    fill_width: boolean('With width', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

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

  const numOfCards = number('Number of cards', 4, generalKnobTab);
  const cards = [];
  for (let itr = 0; itr < numOfCards; itr += 1) {
    cards.push(CardMap[typeOfCard]);
  }

  return CivicCardContainer({
    ...generalKnobs,
    cards,
  });
};
