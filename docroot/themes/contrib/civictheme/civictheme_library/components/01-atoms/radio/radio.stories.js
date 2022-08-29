// phpcs:ignoreFile
import {boolean, radios, text} from '@storybook/addon-knobs';
import CivicThemeRadio from './radio.twig';

export default {
  title: 'Atoms/Radio',
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
    text: text('Text', 'Input label', generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    has_error: boolean('Has error', false, generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeRadio({
    ...generalKnobs,
  });
};
