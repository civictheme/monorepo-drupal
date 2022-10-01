// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeTextfield from './textfield.twig';

export default {
  title: 'Atoms/Form control/Textfield',
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
    is_required: boolean('Required', false, generalKnobTab),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', 'control-name', generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', 'control-id', generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeTextfield({
    ...generalKnobs,
  });
};
