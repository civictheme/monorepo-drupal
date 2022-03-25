import { radios, text } from '@storybook/addon-knobs';
import CivicNextSteps from './next-steps.twig';
import { randomUrl } from '../../00-base/base.stories';

export default {
  title: 'Molecules/Next Steps',
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

  return CivicNextSteps({
    ...generalKnobs,
  });
};
