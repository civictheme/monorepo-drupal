import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeCardContainer from './card-container.twig';
import { PromoCard } from '../../02-molecules/promo-card/promo-card.stories';
import { NavigationCard } from '../../02-molecules/navigation-card/navigation-card.stories';
import { PublicationCard } from '../../02-molecules/publication-card/publication-card.stories';
import { ServiceCard } from '../../02-molecules/service-card/service-card.stories';
import { SubjectCard } from '../../02-molecules/subject-card/subject-card.stories';
import { getSlots } from '../../00-base/base.stories';

export default {
  title: 'Organisms/Card Container',
};

export const CardContainer = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const description = 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Donec velit neque, auctor sit amet aliquam vel, ullamcorper sit amet ligula. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Quisque velit nisi, pretium ut lacinia in, elementum id enim. Curabitur non nulla sit amet nisl tempus convallis quis ac lectus. Cras ultricies ligula sed magna dictum porta. Nulla porttitor accumsan tincidunt.';

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
    description: text('Description', description, generalKnobTab),
    header_link_text: text('Header link Text', 'View all', generalKnobTab),
    header_link_url: text('Header link URL', 'http://example.com', generalKnobTab),
    footer_link_text: text('Footer link Text', 'View all', generalKnobTab),
    footer_link_url: text('Footer link URL', 'http://example.com', generalKnobTab),
    column_count: number(
      'Columns',
      3,
      {
        range: true,
        min: 0,
        max: 4,
        step: 1,
      },
      generalKnobTab,
    ),
    fill_width: boolean('Fill width', false, generalKnobTab),
    with_background: boolean('With background', false, generalKnobTab),
    vertical_space: radios(
      'Vertical space',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const cardsKnobTab = 'Cards';

  const cardType = radios(
    'Card type',
    {
      'Navigation card': 'navigation-card',
      'Promo card': 'promo-card',
      'Publication card': 'publication-card',
      'Service card': 'service-card',
      'Subject card': 'subject-card',
    },
    'promo-card',
    cardsKnobTab,
  );

  const cardsCount = number(
    'Number of cards',
    4,
    {
      range: true,
      min: 0,
      max: 7,
      step: 1,
    },
    cardsKnobTab,
  );

  const cards = [];
  for (let itr = 0; itr < cardsCount; itr += 1) {
    const cardNumber = `Card ${itr + 1}`;
    switch (cardType) {
      case 'navigation-card':
        cards.push(NavigationCard(cardNumber));
        break;
      case 'publication-card':
        cards.push(PublicationCard(cardNumber));
        break;
      case 'service-card':
        cards.push(ServiceCard(cardNumber));
        break;
      case 'subject-card':
        cards.push(SubjectCard(cardNumber));
        break;
      case 'promo-card':
      default:
        cards.push(PromoCard(cardNumber));
    }
  }

  return CivicThemeCardContainer({
    ...generalKnobs,
    cards,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
