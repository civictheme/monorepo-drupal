// phpcs:ignoreFile
import {
  boolean, number, text,
} from '@storybook/addon-knobs';
import CivicThemeItemGrid from './item-grid.twig';
import { generateItems, placeholder } from '../base.utils';

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
    items: generateItems(number(
      'Number of items',
      4,
      {
        range: true,
        min: 0,
        max: 7,
        step: 1,
      },
      generalKnobTab,
    ), placeholder()),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeItemGrid({
    ...generalKnobs,
  });
};
