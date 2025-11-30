// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeLabel from './label.twig';

export default {
  title: 'Atoms/Label',
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
    size: radios(
      'Size',
      {
        'Extra Large': 'extra-large',
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        'Extra Small': 'extra-small',
        None: '',
      },
      'regular',
      generalKnobTab,
    ),
    content: text('Content', 'Label content', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeLabel({
    ...generalKnobs,
  });
};
