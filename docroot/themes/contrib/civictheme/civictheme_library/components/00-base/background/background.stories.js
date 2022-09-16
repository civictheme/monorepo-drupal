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
  const blendMode = select(
    'Blend mode', {
      Color: 'color',
      'Color burn': 'color-burn',
      'Color dodge': 'color-dodge',
      Darken: 'darken',
      Difference: 'difference',
      Exclusion: 'exclusion',
      'Hard light': 'hard-light',
      Hue: 'hue',
      Lighten: 'lighten',
      Luminosity: 'luminosity',
      Multiply: 'multiply',
      Normal: 'normal',
      Overlay: 'overlay',
      Saturation: 'saturation',
      Screen: 'screen',
      'Soft light': 'soft-light',
    },
    'normal',
    generalKnobTab,
  );

  return `<div class="story-background-wrapper story-wrapper-size--large" style="background-image: url('${BACKGROUNDS[bg]}'); background-blend-mode: ${blendMode};"></div>`;
};
