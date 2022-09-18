// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeQuote from './quote.twig';

export default {
  title: 'Molecules/Quote',
  parameters: {
    layout: 'fullscreen',
  },
};

export const Quote = (knobTab) => {
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
    content: text('Content', 'Quote content', generalKnobTab),
    author: text('Author', 'Quote author', generalKnobTab),
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
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional classes', '', generalKnobTab),
  };

  return CivicThemeQuote({
    ...generalKnobs,
  });
};
