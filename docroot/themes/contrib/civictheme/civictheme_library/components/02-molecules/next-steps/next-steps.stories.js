// phpcs:ignoreFile
import { radios, text, boolean } from '@storybook/addon-knobs';
import CivicThemeNextSteps from './next-steps.twig';
import { randomUrl } from '../../00-base/base.stories';

export default {
  title: 'Molecules/Content/Next Steps',
};

export const NextSteps = (knobTab) => {
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
    title: text('Title', 'Next step title from knob', generalKnobTab),
    summary: text('Summary', 'Short summary explaining why this link is relevant.', generalKnobTab),
    url: text('Link', randomUrl(), generalKnobTab),
    is_external: boolean('Is external', false, generalKnobTab),
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
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeNextSteps({
    ...generalKnobs,
  });
};
