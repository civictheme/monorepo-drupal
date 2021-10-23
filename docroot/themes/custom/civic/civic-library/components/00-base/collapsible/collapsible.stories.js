import { radios } from '@storybook/addon-knobs';

import CivicCollapsible from './collapsible.stories.twig';
import './collapsible.scss';
import './collapsible';

export default {
  title: 'Base/Collapsible',
};

export const Collapsible = () => {
  const html = CivicCollapsible({
    theme: radios(
      'Theme', {
        Light: 'light',
        Dark: 'dark',
      },
      'light',
    ),
  });

  return `<div class="story-wrapper-centered story-wrapper-size--medium">${html}</div>`;
};
