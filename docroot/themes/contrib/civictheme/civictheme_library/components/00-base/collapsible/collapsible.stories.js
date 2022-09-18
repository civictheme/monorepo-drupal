// phpcs:ignoreFile
import { radios } from '@storybook/addon-knobs';

import CivicThemeCollapsible from './collapsible.stories.twig';
import './collapsible';

export default {
  title: 'Base/Collapsible',
};

export const Collapsible = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const html = CivicThemeCollapsible({
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    generalKnobTab,
  });

  return `<div class="story-wrapper--centered story-wrapper-size--medium">${html}</div>`;
};
