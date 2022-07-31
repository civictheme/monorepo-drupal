// phpcs:disable Generic.PHP.UpperCaseConstant.Found
// phpcs:disable Squiz.WhiteSpace.OperatorSpacing
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeWebform from './webform.twig';

export default {
  title: 'Organisms/Webform',
};

export const Webform = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const theme = radios(
    'Theme',
    {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
    generalKnobTab,
  );

  const generalKnobs = {
    theme,
    reference_webform: text('Title', 'Webform title', generalKnobTab),
    with_background: boolean('With background', false, generalKnobTab),
    vertical_space: radios(
      'Vertical space',
      {
        None: 'none',
        Top: 'top',
        Bottom: 'bottom',
        Both: 'both',
      },
      'none',
      generalKnobTab,
    ),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeWebform({
    ...generalKnobs,
  });
};
