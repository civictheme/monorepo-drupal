// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeHeading from './heading.twig';

export default {
  title: 'Atoms/Heading',
  parameters: {
    layout: 'centered',
  },
};

export const Heading = (knobTab) => {
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
    level: radios('Level', {
      1: '1',
      2: '2',
      3: '3',
      4: '4',
      5: '5',
      6: '6',
    }, '1', generalKnobTab),
    content: text('Content', 'Heading content', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  return CivicThemeHeading({
    ...generalKnobs,
  });
};
