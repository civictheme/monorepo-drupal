import { radios, text } from '@storybook/addon-knobs';
import CivicNextSteps from './next-steps.twig';

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
    url: text('Link', 'http://example.com', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicNextSteps({
    ...generalKnobs,
  });
};
