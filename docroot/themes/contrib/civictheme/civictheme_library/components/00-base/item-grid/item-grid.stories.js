// phpcs:ignoreFile
import {
  boolean, number, text,
} from '@storybook/addon-knobs';
import CivicThemeItemGrid from './item-grid.twig';

export default {
  title: 'Base/Item Grid',
};

export const ItemGrid = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    column_count: number(
      'Columns',
      4,
      {
        range: true,
        min: 0,
        max: 4,
        step: 1,
      },
      generalKnobTab,
    ),
    fill_width: boolean('Fill width', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const itemsKnobTab = 'Items';

  const itemsCount = number(
    'Number of items',
    4,
    {
      range: true,
      min: 0,
      max: 7,
      step: 1,
    },
    itemsKnobTab,
  );

  const items = [];
  for (let itr = 0; itr < itemsCount; itr += 1) {
    items.push(`<div class="story-slot">Block Placeholder</div>`);
  }

  return CivicThemeItemGrid({
    ...generalKnobs,
    items,
  });
};
