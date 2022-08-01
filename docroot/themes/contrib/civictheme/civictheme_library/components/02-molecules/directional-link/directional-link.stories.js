// phpcs:ignoreFile
import { boolean, radios, text } from '@storybook/addon-knobs';
import CivicThemeDirectionalLink from './directional-link.twig';

export default {
  title: 'Molecules/Directional Link',
  parameters: {
    layout: 'centered',
  },
};

export const DirectionalLink = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const generalKnobs = {
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
      generalKnobTab,
    ),
    direction: radios(
      'Direction', {
        Top: 'top',
        Bottom: 'bottom',
        Left: 'left',
        Right: 'right',
      },
      'left',
      generalKnobTab,
    ),
    text: text('Text', 'Top', generalKnobTab),
    title: text('Title text', 'Top link title', generalKnobTab),
    aria_text: text('ARIA text', 'Top link title', generalKnobTab),
    url: text('URL', '#top', generalKnobTab),
    disabled: boolean('Disabled', false, generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeDirectionalLink({
    ...generalKnobs,
  });
};
