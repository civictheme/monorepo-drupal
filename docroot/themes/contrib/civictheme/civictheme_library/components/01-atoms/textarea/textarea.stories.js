// phpcs:ignoreFile
import {
  boolean, number, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeTextarea from './textarea.twig';

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
    is_invalid: boolean('Invalid', false, generalKnobTab),
    is_disabled: boolean('Disabled', false, generalKnobTab),
    name: text('Name', 'element-name', generalKnobTab),
    id: text('ID', 'element-id', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemeTextarea({
    ...generalKnobs,
  });
};
