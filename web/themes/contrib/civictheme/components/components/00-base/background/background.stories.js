// phpcs:ignoreFile
import { color, select } from '@storybook/addon-knobs';
import { objectFromArray } from '../base.utils';

export default {
  title: 'Base/Background',
  parameters: {
    layout: 'centered',
  },
};

export const Background = (knobTab) => {
  const generalKnobTab = typeof knobTab === 'string' ? knobTab : 'General';

  const bgImageUrl = select('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], generalKnobTab);
  const bgColor = color('Background color', '#003a4f', generalKnobTab);

  const blendMode = select(
    'Blend mode',
    objectFromArray(SCSS_VARIABLES['ct-background-blend-modes']),
    SCSS_VARIABLES['ct-background-blend-modes'][0],
    generalKnobTab,
  );

  return `<div class="story-background-wrapper story-wrapper-size--large ct-background ct-background--${blendMode}" style="background-image: url('${BACKGROUNDS[bgImageUrl]}'); background-color: ${bgColor}"></div>`;
};
