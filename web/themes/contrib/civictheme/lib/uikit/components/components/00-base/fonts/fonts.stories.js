// phpcs:ignoreFile
import merge from 'deepmerge';
import { capitalizeFirstLetter, cleanCssIdentifier } from '../base.utils';

export default {
  title: 'Base/Fonts',
  parameters: {
    layout: 'centered',
  },
};

export const Fonts = () => {
  const fonts = Object.keys(merge(SCSS_VARIABLES['ct-fonts-default'], SCSS_VARIABLES['ct-fonts']));
  const weights = merge(SCSS_VARIABLES['ct-font-weights-default'], SCSS_VARIABLES['ct-font-weights']);

  let html = '';

  for (const i in Object.values(fonts)) {
    html += `<div class="example-container">`;

    html += `<div class="example-container__title">${fonts[i]}</div>`;

    html += `<div class="example-container__content">`;
    for (const weightName in weights) {
      html += `<div class="example-container__subtitle">${capitalizeFirstLetter(weightName)}</div>`;
      html += `<div class="example-container__subcontent story-font--${cleanCssIdentifier(weightName)}--${fonts[i]}">The quick brown fox jumps over the lazy dog</div>`;

      html += `<div class="example-container__subtitle">${capitalizeFirstLetter(weightName)} Italic</div>`;
      html += `<div class="example-container__subcontent story-font--italic--${cleanCssIdentifier(weightName)}--${fonts[i]}">The quick brown fox jumps over the lazy dog</div>`;
    }
    html += `</div>`;

    html += `</div>`;
  }

  return `<div class="story-fonts-wrapper story-wrapper-size--large">${html}</div>`;
};
