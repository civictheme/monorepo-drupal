import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicFilterChipButton from './filter-chip-button.twig';

export default {
  title: 'Atoms/Filter Chip Button',
  parameters: {
    layout: 'centered',
  },
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
