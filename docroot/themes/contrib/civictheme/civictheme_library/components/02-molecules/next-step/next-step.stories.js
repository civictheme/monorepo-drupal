// phpcs:ignoreFile
import { radios, text, boolean } from '@storybook/addon-knobs';
import CivicThemeNextSteps from './next-step.twig';
import { getSlots } from '../../00-base/base.utils';

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
    title: text('Title', 'Next steps title from knob', generalKnobTab),
    content: text('Content', 'Short summary explaining why this link is relevant.', generalKnobTab),
    link: {
      text: text('Link text', 'Sign up', generalKnobTab),
      url: text('Link URL', 'https://example.com', generalKnobTab),
      is_new_window: boolean('Link opens in new window', true, generalKnobTab),
      is_external: boolean('Link is external', true, generalKnobTab),
    },
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
    ...getSlots([
      'content_top',
      'content_bottom',
    ]),
  });
};
