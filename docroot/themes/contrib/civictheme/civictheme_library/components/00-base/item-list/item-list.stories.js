// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';

import CivicThemeItemList from './item-list.twig';
import { generateItems, placeholder, randomSentence } from '../base.utils';

export default {
  title: 'Base/Item List',
  parameters: {
    layout: 'centered',
  },
};

export const ItemList = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    direction: radios(
      'Direction',
      {
        Horizontal: 'horizontal',
        Vertical: 'vertical',
      },
      'horizontal',
      generalKnobTab,
    ),
    size: radios(
      'Size',
      {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
      },
      'regular',
      generalKnobTab,
    ),
    no_gap: boolean('No gap', false, generalKnobTab),
    items: generateItems(number(
      'Items count',
      5,
      {
        range: true,
        min: 0,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ), placeholder(boolean('Long placeholder text', false, generalKnobTab) ? randomSentence(30) : 'Content placeholder')),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: `story-wrapper-size--large ${text('Additional class', '', generalKnobTab)}`,
  };

  return CivicThemeItemList({
    ...generalKnobs,
  });
};
