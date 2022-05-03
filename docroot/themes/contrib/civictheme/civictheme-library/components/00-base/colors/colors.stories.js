export default {
  title: 'Base/Colors',
};

export const Colors = () => {
  const vars = { ...SCSS_VARIABLES };

  const types = {
    'civictheme-colors-default': 'Standard colors',
    'civictheme-colors-default-shades-dark': 'Dark shades',
    'civictheme-colors-default-shades-light': 'Light shades',
    'civictheme-colors-default-neutrals': 'Neutrals',
    'civictheme-colors': 'Custom colors',
  };

  // Show only custom colors without overrides of standard colors.
  vars['civictheme-colors'] = vars['civictheme-colors'].filter((n) => vars['civictheme-colors-default'].indexOf(n) === -1);

  vars['civictheme-colors-default-shades-dark'] = [];
  vars['civictheme-colors-default-shades-light'] = [];
  vars['civictheme-colors-default-neutrals'] = [];

  for (let i = 0; i <= 100; i += 10) {
    vars['civictheme-colors-default-shades-dark'].push(`dark-shade-${i}`);
    vars['civictheme-colors-default-shades-light'].push(`light-shade-${i}`);
    vars['civictheme-colors-default-neutrals'].push(`neutral-${i}`);
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
