// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeTextarea from './textarea.twig';

export default {
  title: 'Atoms/Textarea',
  parameters: {
    layout: 'centered',
  },
};

export const Textarea = (knobTab) => {
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
    rows: number(
      'Number of rows',
      5,
      {
        range: true,
        min: 1,
        max: 10,
        step: 1,
      },
      generalKnobTab,
    ),
    placeholder: text('Placeholder', 'Placeholder', generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeTextarea({
    ...generalKnobs,
  });
};
