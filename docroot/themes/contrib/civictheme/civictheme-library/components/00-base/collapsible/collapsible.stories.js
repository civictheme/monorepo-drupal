import { radios } from '@storybook/addon-knobs';

import CivicCollapsible from './collapsible.stories.twig';
import './collapsible';

export default {
  title: 'Base/Collapsible',
};

export const Collapsible = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';
  const html = CivicCollapsible({
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
    generalKnobTab,
  });

  return `<div class="story-wrapper-centered story-wrapper-size--medium">${html}</div>`;
};
