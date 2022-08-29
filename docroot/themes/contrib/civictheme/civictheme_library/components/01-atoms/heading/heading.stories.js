// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeHeading from './heading.twig';

export default {
  title: 'Atoms/Heading',
};

export const Heading = (knobTab) => {
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
    level: radios('Level', {
      H1: '1',
      H2: '2',
      H3: '3',
      H4: '4',
      H5: '5',
      H6: '6',
    }, '1', generalKnobTab),
    content: text('Content', 'Heading content', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  return CivicThemeHeading({
    ...generalKnobs,
  });
};
