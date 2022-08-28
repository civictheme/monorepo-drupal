// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
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
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeCheckbox({
    ...generalKnobs,
  });
};
