// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeRadio from './radio.twig';

export default {
  title: 'Atoms/Form control/Radio',
  parameters: {
    layout: 'centered',
  },
};

export const Radio = (knobTab, returnHtml = true) => {
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
    label: text('Content', 'Control label', generalKnobTab),
    is_checked: boolean('Checked', false, generalKnobTab),
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', 'control-name', generalKnobTab),
    value: text('Value', 'control-value', generalKnobTab),
    id: text('ID', 'control-id', generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return returnHtml ? CivicThemeRadio({
    ...generalKnobs,
  }) : returnHtml;
};
