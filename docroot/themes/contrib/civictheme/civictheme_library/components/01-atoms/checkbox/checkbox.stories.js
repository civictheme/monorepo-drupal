// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeCheckbox from './checkbox.twig';

export default {
  title: 'Atoms/Form control/Checkbox',
  parameters: {
    layout: 'centered',
  },
};

export const Checkbox = (knobTab, returnHtml = true) => {
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
    label: text('Label', 'Checkbox label that spans on the multiple lines to test vertical checkbox alignment', generalKnobTab),
    is_checked: boolean('Checked', false, generalKnobTab),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', 'control-name', generalKnobTab),
    value: text('Value', 'control-value', generalKnobTab),
    id: text('ID', 'control-id', generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return returnHtml ? CivicThemeCheckbox({
    ...generalKnobs,
  }) : generalKnobs;
};
