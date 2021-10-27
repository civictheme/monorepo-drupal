import {
  boolean, date, number, radios, text,
} from '@storybook/addon-knobs';
import imageFile from '../../../assets/image.png';
import CivicCardContainer from './card-container.twig';
import PromoCard from '../../02-molecules/promo-card/promo-card.twig';
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
    with_spacing: boolean('With spacing', true, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

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
      src: imageFile,
      alt: 'Image alt text',
    } : false,
    modifier_class: text('Additional class', '', cardsKnobTab),
  };

  cardsProps.date = new Date(cardsProps.date).toLocaleDateString('en-uk', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
  });

  const cards = [];
  for (let itr = 0; itr < cardsCount; itr += 1) {
    cards.push(PromoCard(cardsProps));
  }

  return CivicCardContainer({
    ...generalKnobs,
    cards,
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
