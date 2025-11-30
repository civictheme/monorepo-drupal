// phpcs:ignoreFile
import { knobColor, knobSelect, objectFromArray, shouldRender } from '../storybook/storybook.utils';

export default {
  title: 'Base/Background',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'large',
    storyLayoutClass: 'story-background-wrapper',
  },
};

export const Background = (parentKnobs = {}) => {
  const knobs = {
    url: knobSelect('Background', Object.keys(BACKGROUNDS), Object.keys(BACKGROUNDS)[0], parentKnobs.bgImageUrl, parentKnobs.knobTab),
    color: knobColor('Background color', '#003a4f', parentKnobs.bgColor, parentKnobs.knobTab),
    blend_mode: knobSelect(
      'Blend mode',
      objectFromArray(SCSS_VARIABLES['ct-background-blend-modes']),
      SCSS_VARIABLES['ct-background-blend-modes'][0],
      parentKnobs.blendMode,
      parentKnobs.knobTab,
    ),
  };

  return shouldRender(parentKnobs)
    ? `<div class="ct-background ct-background--${knobs.blend_mode}" style="background-image: url('${BACKGROUNDS[knobs.url]}'); background-color: ${knobs.color}"></div>`
    : knobs;
};
