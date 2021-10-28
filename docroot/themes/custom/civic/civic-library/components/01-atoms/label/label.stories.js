import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicLabel from './label.twig';

export default {
  title: 'Atoms/Form',
  parameters: {
    layout: 'centered',
  },
};

export const Label = (knobTab) => {
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
    title: text('Title', 'Label for input', generalKnobTab),
    title_display: radios(
      'Label position',
      {
        Before: 'before',
        After: 'after',
      },
      'before',
      generalKnobTab,
    ),
    required: boolean('Required', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicLabel({
    ...generalKnobs,
  });
};
