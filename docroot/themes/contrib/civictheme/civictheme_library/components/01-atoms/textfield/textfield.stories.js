// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeTextfield from './textfield.twig';

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
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', 'element-name', generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', 'element-id', generalKnobTab),
    placeholder: text('Placeholder', 'Placeholder', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemeTextfield({
    ...generalKnobs,
  });
};
