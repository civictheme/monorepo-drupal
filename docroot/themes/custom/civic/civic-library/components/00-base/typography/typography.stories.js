export default {
  title: 'Base/Typography',
};

export const Typography = () => {
  const typographyNames = [...new Set([
    ...SCSS_VARIABLES['civic-typography-default'],
    ...SCSS_VARIABLES['civic-typography'],
  ])];

  let html = '';

  for (const i in Object.values(typographyNames)) {
    html += `<div class="example-container">`;
    html += `<div class="example-container__title">${typographyNames[i].replace('-', ' ')} <code>${typographyNames[i]}</code></div>`;
    html += `<div class="example-container__content civic-${typographyNames[i]}">The quick brown fox jumps over the lazy dog</div>`;
    html += `</div>`;
  }

  return html;
};
