// phpcs:ignoreFile
import merge from 'deepmerge';
import { capitalizeFirstLetter, cleanCssIdentifier } from '../storybook/storybook.utils';

export default {
  title: 'Base/Fonts',
  parameters: {
    layout: 'centered',
    storyLayoutSize: 'large',
    storyLayoutCenteredHorizontally: true,
    storyLayoutClass: 'story-fonts-wrapper story-wrapper-size--large',
  },
};

export const Fonts = () => {
  const fonts = Object.keys(merge(SCSS_VARIABLES['ct-fonts-default'], SCSS_VARIABLES['ct-fonts']));
  const weights = merge(SCSS_VARIABLES['ct-font-weights-default'], SCSS_VARIABLES['ct-font-weights']);

  let html = '';

  for (const i in Object.values(fonts)) {
    html += `<div class="story-container">`;

    html += `<div class="story-container__title">${capitalizeFirstLetter(fonts[i])}</div>`;

    html += `<div class="story-container__content">`;
    for (const weightName in weights) {
      html += `<div class="story-container__subtitle">${capitalizeFirstLetter(weightName)}</div>`;
      html += `<div class="story-container__subcontent story-font--${cleanCssIdentifier(weightName)}--${fonts[i]}">The quick brown fox jumps over the lazy dog</div>`;

      html += `<div class="story-container__subtitle">${capitalizeFirstLetter(weightName)} Italic</div>`;
      html += `<div class="story-container__subcontent story-font--italic--${cleanCssIdentifier(weightName)}--${fonts[i]}">The quick brown fox jumps over the lazy dog</div>`;
    }
    html += `</div>`;

    html += `</div>`;
  }

  return html;
};
