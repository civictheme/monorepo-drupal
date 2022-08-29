// phpcs:ignoreFile
import {boolean, radios, text} from '@storybook/addon-knobs';
import CivicThemeCheckbox from './checkbox.twig';

export default {
  title: 'Atoms/Checkbox',
  parameters: {
    layout: 'centered',
  },
};

export const Checkbox = (knobTab) => {
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

  return CivicThemeCheckbox({
    ...generalKnobs,
  });
};
