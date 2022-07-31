// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeLabel from './label.twig';

export default {
  title: 'Atoms/Form/Label',
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
    content: text('Content', 'Label text', generalKnobTab),
    required: boolean('Required', false, generalKnobTab),
    title_display: radios(
      'Title display',
      {
        None: '',
        After: 'after',
        Invisible: 'invisible',
      },
      'light',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeLabel({
    ...generalKnobs,
  });
};
