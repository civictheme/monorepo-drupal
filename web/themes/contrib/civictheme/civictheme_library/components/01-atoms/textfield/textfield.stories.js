// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeTextfield from './textfield.twig';

export default {
  title: 'Atoms/Textfield',
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
    placeholder: text('Placeholder', 'Placeholder', generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeTextfield({
    ...generalKnobs,
  });
};
