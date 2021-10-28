import { boolean, radios, text } from '@storybook/addon-knobs';

import CivicInput from './input.twig';

export default {
  title: 'Atoms/Form/Input',
  parameters: {
    layout: 'centered',
  },
};

export const Input = (knobTab) => {
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
    type: radios(
      'Type',
      {
        Text: 'text',
        Textarea: 'textarea',
        Email: 'email',
        Tel: 'tel',
        Password: 'password',
      },
      'text',
      generalKnobTab,
    ),
    value: text('Value', 'Civic input', generalKnobTab),
    placeholder: text('Placeholder', 'Civic input', generalKnobTab),
    autocomplete: boolean('Autocomplete', false, generalKnobTab),
    label: text('Label', 'Civic input label', generalKnobTab),
    state: radios(
      'State',
      {
        None: 'default',
        Error: 'error',
        Success: 'success',
      },
      'default',
      generalKnobTab,
    ),
    disabled: boolean('Disabled', false, generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicInput({
    ...generalKnobs,
  });
};
