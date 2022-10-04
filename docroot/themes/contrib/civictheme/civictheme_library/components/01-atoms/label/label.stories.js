// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeLabel from './label.twig';

export default {
  title: 'Atoms/Form control/Label',
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
      'Size', {
        Large: 'large',
        Regular: 'regular',
        Small: 'small',
        None: '',
      },
      'regular',
      generalKnobTab,
    ),
    content: text('Content', 'Label content', generalKnobTab),
    for: text('For', '', generalKnobTab),
    is_required: boolean('Required', false, generalKnobTab),
    modifier_class: `story-wrapper-size--small ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeLabel({
    ...generalKnobs,
  });
};
