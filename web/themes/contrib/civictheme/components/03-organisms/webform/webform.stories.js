// phpcs:ignoreFile
import {
  boolean, radios, text,
} from '@storybook/addon-knobs';
import CivicThemeWebform from './webform.twig';

export default {
  title: 'Organisms/Webform',
  parameters: {
    layout: 'fullscreen',
  },
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
    referenced_webform: text('Title', 'Webform title', generalKnobTab),
    with_background: boolean('With background', false, generalKnobTab),
    vertical_spacing: radios(
      'Vertical spacing',
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
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeWebform({
    ...generalKnobs,
  });
};
