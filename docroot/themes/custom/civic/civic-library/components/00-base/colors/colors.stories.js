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
  vars['civic-colors'] = vars['civic-colors'].filter((n) => vars['civic-colors-default'].indexOf(n) === -1);

  vars['civic-colors-default-shades-dark'] = [];
  vars['civic-colors-default-shades-light'] = [];
  vars['civic-colors-default-neutrals'] = [];

  for (let i = 0; i <= 100; i += 10) {
    vars['civic-colors-default-shades-dark'].push(`dark-shade-${i}`);
    vars['civic-colors-default-shades-light'].push(`light-shade-${i}`);
    vars['civic-colors-default-neutrals'].push(`neutral-${i}`);
  }

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
