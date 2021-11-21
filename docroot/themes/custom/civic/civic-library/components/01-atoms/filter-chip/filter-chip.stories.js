import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicFilterChip from './filter-chip.twig';
import CivicFilterChipButton from './filter-chip-button.twig';

export default {
  title: 'Atoms/Filter Chip',
  parameters: {
    layout: 'centered',
  },
};

export const FilterChip = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    text: text('Text', 'Filter Chip text', generalKnobTab),
    id: text('Input ID', 'filter-chip-1', generalKnobTab),
    name: text('Input name', 'chip', generalKnobTab),
    is_multiple: boolean('Is multiple', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicFilterChip({
    ...generalKnobs,
  });
};

export const FilterChipButton = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    text: text('Text', 'Filter Chip text', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    is_selected: boolean('Is selected', false, generalKnobTab),
  };

  return CivicFilterChipButton({
    ...generalKnobs,
  });
};
