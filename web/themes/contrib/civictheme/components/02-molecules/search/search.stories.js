// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';
import CivicThemeSearch from './search.twig';

export default {
  title: 'Molecules/Search',
  parameters: {
    layout: 'centered',
  },
};

export const Search = (knobTab) => {
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
    text: text('Text', 'Search', generalKnobTab),
    link: text('Search URL', '/search', generalKnobTab),
    modifier_class: `story-wrapper-size--large ${text('Additional class', '', generalKnobTab)}`,
    attributes: text('Additional attributes', '', generalKnobTab),
  };

  const html = CivicThemeSearch({
    ...generalKnobs,
  });

  return `${html}`;
};
