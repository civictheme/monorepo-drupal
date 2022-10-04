// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeFieldMessage from './field-message.twig';

export default {
  title: 'Atoms/Form control/Field Message',
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
    content: text('Content', 'Content sample with long text that spans on the multiple lines to test text vertical spacing', generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeFieldMessage({
    ...generalKnobs,
  });
};
