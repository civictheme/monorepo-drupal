import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicCheckbox from './checkbox.twig';

export default {
  title: 'Atoms/Form/Checkbox',
  parameters: {
    layout: 'centered',
  },
};

export const Checkbox = (knobTab) => {
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
    value: text('Value', 'Civic input', generalKnobTab),
    label: text('Label', 'Civic input label', generalKnobTab),
    state: radios(
      'State',
      {
        None: 'none',
        Error: 'error',
        Success: 'success',
      },
      'none',
      generalKnobTab,
    ),
    disabled: boolean('Disabled', false, generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicCheckbox({
    ...generalKnobs,
  });
};
