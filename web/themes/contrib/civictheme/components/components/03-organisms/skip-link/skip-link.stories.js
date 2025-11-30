// phpcs:ignoreFile
import { radios, text } from '@storybook/addon-knobs';

import CivicThemeSkipLink from './skip-link.twig';

export default {
  title: 'Organisms/Skip Link',
  parameters: {
    layout: 'fullscreen',
  },
};

export const SkipLink = (knobTab) => {
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
    text: text('Text', 'Skip to main content', generalKnobTab),
    url: text('URL', '#main-content', generalKnobTab),
    modifier_class: text('Additional class', '', generalKnobTab),
  };

  let html = CivicThemeSkipLink({
    ...generalKnobs,
  });

  html += '<div class="docs-container docs-container--large">Press TAB on the keyboard for the Skip Link to appear</div>';

  return html;
};
