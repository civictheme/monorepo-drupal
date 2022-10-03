// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeFieldDescription from './field-description.twig';

export default {
  title: 'Atoms/Form control/Field Description',
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
    size: radios(
      'Size', {
        Large: 'large',
        Regular: 'regular',
        None: '',
      },
      'regular',
      generalKnobTab,
    ),
    content: text('Content', 'Field description that spans on the multiple lines to test vertical checkbox alignment.', generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeFieldDescription({
    ...generalKnobs,
  });
};
