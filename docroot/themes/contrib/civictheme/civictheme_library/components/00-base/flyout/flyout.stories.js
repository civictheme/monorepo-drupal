// phpcs:ignoreFile
import { boolean, number, radios } from '@storybook/addon-knobs';

import './flyout';
import CivicThemeFlyout from './flyout.stories.twig';

export default {
  title: 'Base/Flyout',
  parameters: {
    layout: 'centered',
  },
};

export const Flyout = () => {
  const html = CivicThemeFlyout({
    direction: radios(
      'Flyout from',
      {
        Top: 'top',
        Bottom: 'bottom',
        Left: 'left',
        Right: 'right',
      },
      'right',
    ),
    expanded: boolean('Expanded', false),
    duration: number('Duration (ms)', 500),
  });

  return `<div class="story-wrapper-size--medium">${html}</div>`;
};
