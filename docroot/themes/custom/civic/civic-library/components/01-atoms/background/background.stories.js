import { select } from '@storybook/addon-knobs';

export default {
  title: 'Atom/Background',
  parameters: {
    layout: 'centered',
  },
};

export const Background = () => {
  const bg = select('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0]);

  return `<div class="story-backgrounds-wrapper story-wrapper-size--large"><img src="${BACKGROUNDS[bg]}" style="max-width: 100%"/></div>`;
};
