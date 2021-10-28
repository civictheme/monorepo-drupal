import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicRadio from './radio.twig';

export default {
  title: 'Atoms/Form/Radio',
  parameters: {
    layout: 'centered',
  },
};

export const Radio = (knobTab) => {
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
    type: 'radio',
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

  return CivicRadio(generalKnobs);
};
