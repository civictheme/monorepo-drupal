import { radios, text, select } from '@storybook/addon-knobs';
import CivicSearch from './search.twig';

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
    placeholder: text('Placeholder', 'Enter keywords or phrase', generalKnobTab),
    search_url: text('Searcyh URL', '/search', generalKnobTab),
    button_text: text('Button text', 'Search', generalKnobTab),
    button_type: select('Button type', [
      'primary',
      'secondary',
      'tertiary',
    ], 'primary', generalKnobTab),
    help_text: text('Description', 'Search by keyword', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  const html = CivicSearch({
    ...generalKnobs,
  });

  return `<div class="story-wrapper-size--large">${html}</div>`;
};
