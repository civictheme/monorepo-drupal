import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicTextfield from './textfield.twig';

export default {
  title: 'Atoms/Form/Textfield',
  parameters: {
    layout: 'centered',
  },
};

export const Textfield = (knobTab) => {
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
    text: text('Text', 'Textfield text', generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicTextfield({
    ...generalKnobs,
  });
};
