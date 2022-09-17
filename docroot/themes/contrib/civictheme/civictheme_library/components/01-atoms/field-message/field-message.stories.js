// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeFieldMessage from './field-message.twig';

export default {
  title: 'Atoms/Field Message',
  parameters: {
    layout: 'centered',
  },
};

export const FieldMessage = (knobTab) => {
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
    type: radios(
      'Type',
      {
        Error: 'error',
        Information: 'information',
        Warning: 'warning',
        Success: 'success',
      },
      'error',
      generalKnobTab,
    ),
    content: text('Content', 'Field message content', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeFieldMessage({
    ...generalKnobs,
  });
};
