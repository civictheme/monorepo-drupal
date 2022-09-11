// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeFieldDescription from './field-description.twig';

export default {
  title: 'Atoms/Field Description',
  parameters: {
    layout: 'centered',
  },
};

export const FieldDescription = (knobTab) => {
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
    content: text('Content', 'We will only use this to respond to your question.', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeFieldDescription({
    ...generalKnobs,
  });
};
