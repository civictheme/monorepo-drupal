// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeTextfield from './textfield.twig';
import { indexedString } from '../../00-base/base.stories';

export default {
  title: 'Atoms/Form control/Textfield',
  parameters: {
    layout: 'centered',
  },
};

export const Textfield = (knobTab, returnHtml = true, idx = null) => {
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
    name: text('Name', indexedString('control-name', idx), generalKnobTab),
    value: text('Value', '', generalKnobTab),
    id: text('ID', indexedString('control-id', idx), generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return returnHtml ? CivicThemeTextfield({
    ...generalKnobs,
  }) : generalKnobs;
};
