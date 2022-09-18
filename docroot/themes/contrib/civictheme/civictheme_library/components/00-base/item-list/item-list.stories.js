// phpcs:ignoreFile
import { number, radios, text } from '@storybook/addon-knobs';

import CivicThemeItemList from './item-list.twig';
import { generateItems, placeholder } from '../base.stories';

export default {
  title: 'Base/Item List',
  parameters: {
    layout: 'centered',
  },
};

export const ItemList = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    type: radios(
      'Type',
      {
        Horizontal: 'horizontal',
        Vertical: 'vertical',
      },
      'horizontal',
      generalKnobTab,
    ),
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
    ), placeholder()),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeItemList({
    ...generalKnobs,
  });
};
