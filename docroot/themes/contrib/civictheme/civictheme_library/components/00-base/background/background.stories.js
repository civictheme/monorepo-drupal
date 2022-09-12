// phpcs:ignoreFile
import { select } from '@storybook/addon-knobs';

export default {
  title: 'Base/Background',
  parameters: {
    layout: 'centered',
  },
};

export const Background = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const bg = select('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], generalKnobTab);

  return `<div class="story-background-wrapper story-wrapper-size--large" style="background-image: url('${BACKGROUNDS[bg]}')"></div>`;
};
