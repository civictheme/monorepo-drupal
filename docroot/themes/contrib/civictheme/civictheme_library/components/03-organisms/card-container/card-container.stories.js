import {
  boolean, date, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeCardContainer from './card-container.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
import NavigationCard from '../../02-molecules/navigation-card/navigation-card.twig';
import PublicationCard from '../../02-molecules/publication-card/publication-card.twig';
import ServiceCard from '../../02-molecules/service-card/service-card.twig';
import SubjectCard from '../../02-molecules/subject-card/subject-card.twig';
import { demoImage, getSlots, randomTags, randomLinks } from '../../00-base/base.stories';

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

  const cardType = radios(
    'Card type',
    {
      'Navigation card': 'navigation-card',
      'Promo card': 'promo-card',
      'Publication card': 'publication-card',
      'Service card': 'service-card',
      'Subject card': 'subject-card',
      'Random': 'random',
    },
    'promo-card',
    generalKnobTab,
  );

  const cardsKnobTab = 'Cards';
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

  const cardsProps = {
    theme: radios(
      'Theme',
      {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      cardsKnobTab,
    ),
    date: date('Date', new Date(), cardsKnobTab),
    title: text('Title', 'Event name which runs across two or three lines', cardsKnobTab),
    summary: text('Summary', 'Card summary using body copy which can run across multiple lines. Recommend limiting this summary to three or four lines..', cardsKnobTab),
    url: text('Link URL', 'http://example.com', cardsKnobTab),
    image: boolean('With image', true, cardsKnobTab) ? {
      src: demoImage(),
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', cardsKnobTab),
  };

  if (cardType == 'navigation-card' || cardType == 'promo-card' || cardType == 'random') {
    cardsProps.tags = randomTags(number(
      'Number of tags',
      2,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      cardsKnobTab,
    ), true);
  }

  if (cardType == 'navigation-card' || cardType == 'random') {
    cardsProps.is_external = boolean('Is external', false, cardsKnobTab);
  }

  if (cardType == 'service-card' || cardType == 'random') {
    cardsProps.links = randomLinks(number(
      'Number of links',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      cardsKnobTab,
    ), 10);
  }

  if (cardType == 'publication-card' || cardType == 'random') {
    cardsProps.link = boolean('With file', true, cardsKnobTab) ? {
      url: 'https://file-examples-com.github.io/uploads/2017/02/file-sample_100kB.doc',
      text: 'Filename.pdf (175.96KB)',
    } : null;
  }

  cardsProps.date = new Date(cardsProps.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  const cardTypes = [
    NavigationCard(cardsProps),
    PromoCard(cardsProps),
    PublicationCard(cardsProps),
    ServiceCard(cardsProps),
    SubjectCard(cardsProps),
  ]
  const cards = [];
  for (let itr = 0; itr < cardsCount; itr += 1) {
    switch (cardType) {
      case "navigation-card":
        cards.push(cardTypes[0]);
        break;
      case "promo-card":
        cards.push(cardTypes[1]);
        break;
      case "publication-card":
        cards.push(cardTypes[2]);
        break;
      case "service-card":
        cards.push(cardTypes[3]);
        break;
      case "subject-card":
        cards.push(cardTypes[4]);
        break;
      case "random":
      default:
        cards.push(cardTypes[Math.floor(Math.random() * 5)]);
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
