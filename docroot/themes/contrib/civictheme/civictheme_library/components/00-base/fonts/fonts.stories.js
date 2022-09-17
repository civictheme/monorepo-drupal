// phpcs:ignoreFile
import merge from 'deepmerge';

export default {
  title: 'Base/Fonts',
  parameters: {
    layout: 'centered',
  },
};

export const Fonts = () => {
  const defaultValues = SCSS_VARIABLES['civictheme-fonts-default'];
  const customValues = SCSS_VARIABLES['civictheme-fonts'];
  const fonts = Object.keys(merge(defaultValues, customValues));

  const types = {
    Regular: 'regular',
    Italic: 'italic',
    Thin: 'thin',
    Bold: 'bold',
    'Extra Bold': 'extra-bold',
    'Bold Italic': 'bold-italic',
  };

  let html = '';

  for (const i in Object.values(fonts)) {
    html += `<div class="example-container">`;

    html += `<div class="example-container__title">${fonts[i]}</div>`;

    html += `<div class="example-container__content">`;
    for (const name in types) {
      html += `<div class="example-container__subtitle">${name}</div>`;
      html += `<div class="example-container__subcontent story-font--${types[name]}--${fonts[i]}">The quick brown fox jumps over the lazy dog</div>`;
    }
    html += `</div>`;

    html += `</div>`;
  }

  return `<div class="story-fonts-wrapper story-wrapper-size--large">${html}</div>`;
};
