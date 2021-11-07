export default {
  title: 'Base/Colors',
};

export const Colors = () => {
  const types = {
    'civic-colors-default': 'Standard colors',
    'civic-colors-default-shades': 'Shades',
    'civic-colors-default-neutrals': 'Neutrals',
    'civic-colors': 'Custom colors',
  };

  let html = '';

  for (const name in types) {
    if (Object.values(SCSS_VARIABLES[name]).length > 0) {
      html += `<div class="example-container">`;
      html += `<div class="example-container__title">${types[name]}</div>`;
      html += `<div class="example-container__content story-colors-wrapper story-wrapper-size--large">`;
      for (const i in Object.values(SCSS_VARIABLES[name])) {
        html += `<div class="example-container__content story-color--${SCSS_VARIABLES[name][i]}"></div>`;
      }
      html += `</div>`;
      html += `</div>`;
    }
  }

  return html;
};
