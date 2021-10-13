import { radios, select } from '@storybook/addon-knobs';

export default {
  title: 'Atom/Background',
  parameters: {
    layout: 'centered',
  },
};

export const Background = () => {
  const theme = radios(
    'Theme', {
      Light: 'light',
      Dark: 'dark',
    },
    'light',
  );

  const bg = select('Background', Object.keys(BACKGROUNDS[theme]), Object.keys(BACKGROUNDS[theme])[0]);

  return `<div class="story-backgrounds-wrapper story-wrapper-size--large"><img src="${BACKGROUNDS[theme][bg]}" style="max-width: 100%"/></div>`;
};
