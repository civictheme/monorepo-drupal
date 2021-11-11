export default {
  title: 'Base/Colors',
};

export const Colors = () => {
  const vars = { ...SCSS_VARIABLES };

  const types = {
    'civic-colors-default': 'Standard colors',
    'civic-colors-default-shades-dark': 'Dark shades',
    'civic-colors-default-shades-light': 'Light shades',
    'civic-colors-default-neutrals': 'Neutrals',
    'civic-colors': 'Custom colors',
  };

  // Show only custom colors without overrides of standard colors.
  const standardTypes = vars['civic-colors-default'].concat(vars['civic-colors-default-shades-dark'], vars['civic-colors-default-shades-light'], vars['civic-colors-default-neutrals']);
  vars['civic-colors'] = vars['civic-colors'].filter((n) => standardTypes.indexOf(n) === -1);

  let html = '';

  for (const name in types) {
    if (Object.values(vars[name]).length > 0) {
      html += `<div class="example-container">`;
      html += `<div class="example-container__title">${types[name]}</div>`;
      html += `<div class="example-container__content story-colors-wrapper story-wrapper-size--large">`;
      for (const i in Object.values(vars[name])) {
        html += `<div class="example-container__content story-color--${vars[name][i]}"></div>`;
      }
      html += `</div>`;
      html += `</div>`;
    }
  }

  return html;
};
