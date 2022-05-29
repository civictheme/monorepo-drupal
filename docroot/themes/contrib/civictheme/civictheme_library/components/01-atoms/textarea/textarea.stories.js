import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicTextarea from './textarea.twig';

export default {
  title: 'Atoms/Form/Textarea',
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
    text: text('Text', 'Textarea text', generalKnobTab),
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
    required: boolean('Required', false, generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicTextarea({
    ...generalKnobs,
  });
};
