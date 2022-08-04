// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicThemeBasicFilter from './basic-filter.twig';

export default {
  title: 'Molecules/Filter/Basic Filter',
  parameters: {
    layout: 'centered',
  },
};

export const BasicFilter = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

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
    is_multiple: boolean('Multiple', false, generalKnobTab),
    items: boolean('With items', true, generalKnobTab) ? [
      {
        text: text('Text', 'Basic filter 1', generalKnobTab),
      },
      {
        text: text('Text', 'Basic filter 2', generalKnobTab),
      },
      {
        text: text('Text', 'Basic filter 3', generalKnobTab),
      },
    ] : null,
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeBasicFilter({
    ...generalKnobs,
  });
};
